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

use Malenki\Ansi\Effect;
use Malenki\Ansi\StringBuilder;
use Malenki\Ansi\OutputBuilder;

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


    /**
     * Stores the string to format/colorize.
     *
     * @var string
     * @access protected
     */
    protected $str = '';
    protected $has_tags = false;

    /**
     * @var Ansi\OutputBuilder
     */
    protected $output;
    /**
     * Format code.
     *
     * Default to 0, no effect.
     *
     * @var Ansi\Effect
     * @access protected
     */
    protected $format = 0;



    public function __get($name)
    {
        $std_colors  = Ansi\Color::getStandardNames();
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
            return $this->applyEffect($name);
        }
    }

    public function __call($name, $arguments)
    {
        $std_effects = Ansi\Effect::getStandardNames();

        if (in_array($name, $std_effects)) {
            return $this->applyEffect($name);
        }
    }

    protected function applyEffect($name)
    {
        $this->format->choose($name);
        return $this;
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
        $this->value($str);
        $this->output = new Ansi\OutputBuilder($this->str);
        $this->format = new Ansi\Effect();
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

        $this->output->addLayer($layer, $color);
    }

    public function value($str)
    {

        if (is_scalar($str)) {
            $this->str = $str;
        } else {
            throw new \InvalidArgumentException('The constructor’s argument must be a scalar value!');
        }

        $this->has_tags = $this->tag_format->hasTags($this->str);

        if ($this->output) {
            $this->output->setText($this->str);
        }

        /*
        if (!is_scalar($str)) {
            throw new \InvalidArgumentException('To set new value, you must use scalar argument.');
        }

        $this->str = $str;
        $this->output->setText($str);
        */
        
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

    protected function checkAnsiAwareSystem()
    {
        // if on windows system, no ANSI!
        return DIRECTORY_SEPARATOR !== '\\';
    }

    /**
     * Renders the text with color and effet applied.
     *
     * @access public
     * @return string
     */
    public function render()
    {
        if (!$this->checkAnsiAwareSystem()) {
            return $this->str;
        }

        if ($this->has_tags) {
            return $this->tag_format->parse($this->str);
        } elseif (!empty($this->str)) {
            $this->output->useEffect($this->format);
            return $this->output->build();
        }

        return $this->str;
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
