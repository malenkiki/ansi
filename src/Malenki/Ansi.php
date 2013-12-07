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
 * **Note:** Some effects are not available into some terminal.
 * 
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Ansi
{
    /**
     * Stores the original string. 
     * 
     * @var string
     * @access protected
     */
    protected $str = null;

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



    /**
     * Construct takes the string to format as argument.
     * 
     * @throws \InvalidArgumentException If argument is not a not null string
     * @param string $str 
     * @access public
     * @return void
     */
    public function __construct($str)
    {
        if(is_string($str) && strlen($str))
        {
            $this->str = $str;
        }
        else
        {
            throw new \InvalidArgumentException('Invalid string!');
        }
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
        if(array_key_exists($name, self::$arr_fg))
        {
            $this->fg = self::$arr_fg[$name];
        }
        else
        {
            throw \InvalidArgumentException(
                sprintf('Foreground color "%s" does not exist!', $name)
            );
        }

        return $this;
    }



    /**
     * Sets background color by giving its name. 
     * 
     * Available valid names are: `black`, `red`, `green`, `yellow`, `blue`, 
     * `magenta`, `cyan` and `gray`.
     *
     * @use self::$arr_bg
     * @param string $name Color's name
     * @access public
     * @return Ansi
     */
    public function background($name)
    {
        if(array_key_exists($name, self::$arr_bg))
        {
            $this->bg = self::$arr_bg[$name];
        }
        else
        {
            throw \InvalidArgumentException(
                sprintf('Background color "%s" does not exist!', $name)
            );
        }

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
