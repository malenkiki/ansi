<?php
/*
 * Copyright (c) 2013 Michel Petit <petit.michel@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


namespace Malenki;

/**
 * Ansi colored string.
 *
 * This class allows you to have color into you terminal PHP applications.
 *
 * In addition to foreground and background colors, you can use underline, 
 * bold, faint and italic.
 *
 * Effect and color can be add together, if terminal is able to render it.
 *
 * **Note 1:** Some effects are not available into some terminal.
 * 
 * **Note 2:** windows terminal **does not support ANSI codes**, so, this is 
 * usefull only on UNIX-like systems. If you use this class on MS Windows 
 * system, then output and input string are the same. 
 *
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Ansi
{
    /**
     * Stores the string to format/colorize. 
     * 
     * @var string
     * @access protected
     */
    protected $str = '';

    /**
     * Foreground code to use. 
     * 
     * Default is set to 39 (default on the system).
     *
     * @var integer
     * @access protected
     */
    protected $fg = 39;

    /**
     * Background code to use. 
     * 
     * Default is set to 49 (default on the system).
     *
     * @var integer
     * @access protected
     */
    protected $bg = 49;


    /**
     * Format code. 
     * 
     * Default to 0, no effect.
     *
     * @var integer
     * @access protected
     */
    protected $format = 0;


    protected $is_special = false;

    /**
     * Links from foreground color's name to its code
     */
    protected static $arr_fg = array(
        'black'  => 30,
        'red'    => 31,
        'green'  => 32,
        'yellow' => 33,
        'blue'   => 34,
        'purple' => 35,
        'cyan'   => 36,
        'white'  => 37,
    );



    /**
     * Links from background color's name to its code
     */
    protected static $arr_bg = array(
        'black'   => 40,
        'red'     => 41,
        'green'   => 42,
        'yellow'  => 43,
        'blue'    => 44,
        'magenta' => 45,
        'cyan'    => 46,
        'gray'    => 47
    );



    protected static function parseDom($dom, &$str_out)
    {
        if($dom->childNodes)
        {

            for($i = 0; $i < $dom->childNodes->length; $i++)
            {
                $nodes = $dom->childNodes->item($i);

                //var_dump('Node found: '.$dom->localName);
                self::parseDom($nodes, $str_out);
            }

        }
        else
        {
            $arr_tag_names = explode('/', trim($dom->getNodePath(),'/'));
            array_shift($arr_tag_names);
            array_pop($arr_tag_names);
            $a = new self($dom->nodeValue);

            $arr_bg = array_keys(self::$arr_bg);

            $arr_effects = array_keys(self::$arr_fg);
            $arr_effects = array_merge($arr_effects, array('faint', 'bold', 'italic', 'underline'));

            foreach($arr_tag_names as $effect)
            {
                if(in_array($effect, $arr_effects))
                {
                    $a->$effect;
                }
                elseif(preg_match('/^bg_/', $effect))
                {
                    $effectbg = preg_replace('/^bg_/', '', $effect);

                    if(in_array($effectbg, $arr_bg))
                    {
                        $a->bg($effectbg);
                    }
                }
            }

            $str_out .= $a;
        }
    }



    public static function parse($str)
    {
        if(preg_match("/\<.+\>.+\<\/.+\>/U",$str))
        {
            if(!extension_loaded('dom'))
            {
                trigger_error(
                    'DOM extension is not available! It is needed to parse string with tags!',
                    E_USER_WARNING
                );

                return strip_tags($str);
            }

            $dom = new \DOMDocument('1.0');

            if(!$dom->loadXML('<doc>'.$str.'</doc>'))
            {
                throw new \InvalidArgumentException('Your string has malformed tags!');
            }

            $str_out = '';

            self::parseDom($dom, $str_out);

            return $str_out;
        }
        else
        {
            return $str;
        }
    }



    public function __get($name)
    {
        if(in_array($name, array_keys(self::$arr_fg)))
        {
            return $this->fg($name);
        }
        
        if(preg_match('/^bg_/', $name) && in_array(preg_replace('/^bg_/', '', $name), array_keys(self::$arr_bg)))
        {
            return $this->bg(preg_replace('/^bg_/', '', $name));
        }


        if(in_array($name, array('faint', 'bold', 'italic', 'underline')))
        {
            return $this->$name();
        }
    }



    /**
     * Constructor takes the string to format as argument or nothing.
     * 
     * @throws \InvalidArgumentException If given argument is not a not string
     * @param string $str 
     * @access public
     * @return void
     */
    public function __construct($str = '')
    {
        if(DIRECTORY_SEPARATOR == '\\')
        {
            trigger_error(
                'ANSI codes are not available on MS Windows systems!',
                E_USER_NOTICE
            );
        }
        
        if(is_string($str))
        {
            $this->str = $str;
        }
        else
        {
            throw new \InvalidArgumentException('The constructor’s argument must be a string!');
        }
    }


    protected function setColor($type, $name)
    {
        if(is_string($name))
        {
            if($type == 'bg' && array_key_exists($name, self::$arr_bg))
            {
                $this->$type = self::$arr_bg[$name];
            }
            elseif($type == 'fg' && array_key_exists($name, self::$arr_fg))
            {
                $this->$type = self::$arr_fg[$name];
            }
            else
            {
                throw \InvalidArgumentException(
                    sprintf('Color "%s" does not exist!', $name)
                );
            }
        }
        else
        {
            if(is_array($name) && count($name) >= 3)
            {
                if(isset($name['r']))
                {
                    $name = (object) $name;
                }
                else
                {
                    $new = new \stdClass();
                    $new->r = $name[0];
                    $new->g = $name[1];
                    $new->b = $name[2];

                    $name = $new;
                }
            }

            // RGB 256-like colors (xterm)
            if(is_object($name) && isset($name->r) && isset($name->g) && isset($name->b))
            {
                if(
                    in_array($name->r, range(0, 5))
                    &&
                    in_array($name->g, range(0, 5))
                    &&
                    in_array($name->b, range(0, 5))
                )
                {
                    $this->$type = 16 + 36 * $name->r + 6 * $name->g + $name->b;
                    $this->is_special = true;
                }
            }

            // Grayscale having 24 levels
            if(is_numeric($name) && in_array($name, range(0, 23)))
            {
                $this->$type = 0xE8 + $name;
                $this->is_special = true;
            }
        }

    }


    public function value($str)
    {
        if(!is_string($str))
        {
            throw new \InvalidArgumentException('To set new value, you must use string argument.');
        }

        $this->str = $str;

        return $this;
    }



    public function v($str)
    {
        return $this->value($str);
    }



    /**
     * Sets foreground color by giving its name. 
     * 
     * Available valid names are: `black`, `red`, `green`, `yellow`, `blue`, 
     * `purple`, `cyan` and `white`.
     *
     * @use self::$arr_fg
     * @param string $name 
     * @access public
     * @return Ansi
     */
    public function foreground($name)
    {
        $this->setColor('fg', $name);
        return $this;
    }



    /**
     * Sets background color by giving its name. 
     * 
     * Available valid names are: `black`, `red`, `green`, `yellow`, `blue`, 
     * `magenta`, `cyan` and `gray`.
     *
     * @use self::$arr_bg
     * @param mixed $name Color's name or value
     * @access public
     * @return Ansi
     */
    public function background($name)
    {
        $this->setColor('bg', $name);
        return $this;
    }



    /**
     * Shorthand for Ansi::foreground() method. 
     * 
     * @use Ansi::foreground();
     * @param string $name Color's name
     * @access public
     * @return Ansi
     */
    public function fg($name)
    {
        return $this->foreground($name);
    }



    /**
     * Shorthand for Ansi::background() method. 
     * 
     * @use Ansi::background();
     * @param string $name Color's name
     * @access public
     * @return Ansi
     */
    public function bg($name)
    {
        return $this->background($name);
    }




    /**
     * Sets text as bold 
     * 
     * @access public
     * @return Ansi
     */
    public function bold()
    {
        $this->format = 1;

        return $this;
    }



    /**
     * Sets text as faint. 
     * 
     * @access public
     * @return Ansi
     */
    public function faint()
    {
        $this->format = 2;

        return $this;
    }




    /**
     * Sets text as italic.
     * 
     * @access public
     * @return Ansi
     */
    public function italic()
    {
        $this->format = 3;

        return $this;
    }



    /**
     * Underlines the text.
     * 
     * @access public
     * @return Ansi
     */
    public function underline()
    {
        $this->format = 4;

        return $this;
    }



    /**
     * Renders the text with color and effet applied.
     * 
     * @access public
     * @return string
     */
    public function render()
    {
        if(!empty($this->str))
        {
            // if on windows system, no ANSI!
            if(DIRECTORY_SEPARATOR == '\\')
            {
                return $this->str;
            }

            $arr_out = array();

            if($this->fg)
            {
                $arr_out[] = sprintf("\033[%d;%dm", $this->format, $this->fg);
            }

            if($this->bg)
            {
                $arr_out[] = sprintf("\033[%dm", $this->bg);
            }

            $arr_out[] = $this->str;

            if($this->format)
            {
                $arr_out[] = sprintf("\033[2%dm", $this->format);
            }

            $arr_out[] = "\033[0m";

            return implode('', $arr_out);
        }
        else
        {
            return $this->str;
        }
    }



    /**
     * In string context, renders the string with all effects applied. 
     * 
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
