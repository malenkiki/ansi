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
    protected $tag_format;
    protected $layers = array();

    /**
     * Stores the string to format/colorize.
     *
     * @var string
     * @access protected
     */
    protected $str = '';
    protected $has_tags = false;


    /**
     * Foreground extended code to use.
     *
     * Default is set to 38 (default on the system).
     *
     * @var integer
     * @access protected
     */
    protected $fg_extended = 38;
    protected $fg_extended_value = null;


    /**
     * Background extended code to use.
     *
     * Default is set to 48 (default on the system).
     *
     * @var integer
     * @access protected
     */
    protected $bg_extended = 48;
    protected $bg_extended_value = 0;

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


    public function __get($name)
    {
        $std_colors = Ansi\Color::getStandardNames();
        $std_effects = Ansi\Effect::getStandardNames();

        if (in_array($name, $std_colors)) {
            return $this->fg($name);
        }

        if (
            preg_match('/^bg_/', $name)
            && in_array(preg_replace('/^bg_/', '', $name), $std_colors)
        ) {
            return $this->bg(preg_replace('/^bg_/', '', $name));
        }

        if (in_array($name, $std_effects)) {
            return $this->$name();
        }
    }

    /**
     * Constructor takes the string to format as argument or nothing.
     *
     * @throws \InvalidArgumentException If given argument is not a not string
     * @param  string                    $str
     * @access public
     * @return void
     */
    public function __construct($str = '')
    {
        if (DIRECTORY_SEPARATOR == '\\') {
            trigger_error(
                'ANSI codes are not available on MS Windows systems!',
                E_USER_NOTICE
            );
        }

        $this->tag_format = new Ansi\TagFormat();

        if (is_string($str)) {
            $this->str = $str;
            $this->has_tags = $this->tag_format->hasTags($str);
        } else {
            throw new \InvalidArgumentException('The constructor’s argument must be a string!');
        }
    }

    protected function setColor($type, $name)
    {
        $layer = new Ansi\Layer();
        $layer->choose($type);

        $color = new Ansi\Color();
        $color->choose($name);

        if (!$color->isValid()) {
            throw new \InvalidArgumentException('Given color cannot be used');
        }

        $layer->setColor($color);
        $this->layers[$layer->getCode()] = $layer;
    }

    public function value($str)
    {
        // TODO scalar in place of string test ?
        if (!is_string($str)) {
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
     * @param  string $name
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
     * @param  mixed $name Color's name or value
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
     * @param  string $name Color's name
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
     * @param  string $name Color's name
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
        // TODO à refactoriser
        if ($this->has_tags) {
            return $this->tag_format->parse($this->str);
        } else if (!empty($this->str)) {
            // if on windows system, no ANSI!
            if (DIRECTORY_SEPARATOR == '\\') {
                return $this->str;
            }

            $arr_out = array();

            if ($this->is_special) {
                if (!is_null($this->fg_extended_value)) {
                    $arr_out[] = sprintf(
                        "\033[%d;5;%dm",
                        $this->fg_extended,
                        $this->fg_extended_value
                    );
                }
                if (!is_null($this->bg_extended_value)) {
                    $arr_out[] = sprintf(
                        "\033[%d;5;%dm",
                        $this->bg_extended,
                        $this->bg_extended_value
                    );
                }
            } else {
                if ($this->fg) {
                    $arr_out[] = sprintf("\033[%d;%dm", $this->format, $this->fg);
                }

                if ($this->bg) {
                    $arr_out[] = sprintf("\033[%dm", $this->bg);
                }
            }


            $arr_out[] = $this->str;

            if ($this->format) {
                $arr_out[] = sprintf("\033[2%dm", $this->format);
            }

            $arr_out[] = "\033[0m";

            return implode('', $arr_out);
        } else {
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
        try {
            return $this->render();
        } catch (\Exception $e) {
            return $this->str;
        }
    }
}
