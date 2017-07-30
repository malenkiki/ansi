<?php
/*
 * Copyright (c) 2017 Michel Petit <petit.michel@gmail.com>
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

namespace Malenki\Ansi;

class Color
{
    const MODE_16_COLORS     = 0xFC;
    const MODE_256_COLORS    = 0xFFC;
    const MODE_256_GRAYSCALE = 0xFF6;
    const MODE_TRUE_COLORS   = 0xFFFC;


    protected static $std_16_colors = array(
        'default' => 9,
        'black' => 0,
        'red' => 1,
        'green' => 2,
        'yellow' => 3,
        'blue' => 4,
        'purple' => 5,
        'magenta' => 5,
        'cyan' => 6,
        'white' => 7,
        'gray' => 7
        /*
        'default' => array('fg' => 39, 'bg' => 49),
        'black'   => array('fg' => 30, 'bg' => 40),
        'red'     => array('fg' => 31, 'bg' => 41),
        'green'   => array('fg' => 32, 'bg' => 42),
        'yellow'  => array('fg' => 33, 'bg' => 43),
        'blue'    => array('fg' => 34, 'bg' => 44),
        'purple'  => array('fg' => 35, 'bg' => 45), //only FG in real
        'magenta' => array('fg' => 35, 'bg' => 45), //only BG in real
        'cyan'    => array('fg' => 36, 'bg' => 46),
        'white'   => array('fg' => 37, 'bg' => 47), //only FG in real
        'gray'    => array('fg' => 37, 'bg' => 47) // only BG in real
        */
    );

    protected $ext_256_colors;

    protected $ext_grayscale_colors;

    protected $humanRgbModeNames = array( 'rgb256', 'rgb' );

    protected $mode = null;
    protected $value = null;

    public static function getStandardNames()
    {
        return array_keys(self::$std_16_colors);
    }

    public function __construct()
    {
        $this->ext_256_colors = range(0, 0xFF);
        $this->ext_grayscale_colors = range(0xE8, 0xFF);
    }

    public function isDefault()
    {
        return (
            $this->mode === self::MODE_16_COLORS
            &&
            $this->value === self::$std_16_colors['default']
        );
    }

    protected function arrToObj(&$color)
    {
        $corres = array('r', 'g', 'b', 'm');

        if (is_array($color) && count($color) >= 3) {
            foreach ($corres as $idx => $attr) {
                if (!isset($color[$attr]) && isset($color[$idx])) {
                    $color[$attr] = $color[$idx];
                }
            }
            foreach ($color as $k => $v) {
                if (is_integer($k)) {
                    unset($color[$k]);
                }
            }

            $color = (object) $color;
        }
    }

    protected function guessString($color)
    {
        return (
            is_string($color)
            && array_key_exists($color, self::$std_16_colors)
        );
    }

    protected function guessStringGrayscale($color)
    {
        return (
            is_string($color)
            && preg_match('/^grayscale_([1-9]{1}|1[0-9]{1}|2[0-4]{1})$/', $color)
        );
    }

    protected function guessIntegerRgbMode($color)
    {
        return is_integer($color) && in_array($color, $this->ext_256_colors);
    }

    protected function guessStructuredRgbModeCommon($color)
    {
        if (!is_object($color)) {
            return false;
        }


        $hasRed   = property_exists($color, 'r');
        $hasGreen = property_exists($color, 'g');
        $hasBlue  = property_exists($color, 'b');

        $hasRgb = $hasRed && $hasGreen && $hasBlue;

        if (!$hasRgb) {
            return false;
        }

        foreach ($color as $k => $v) {
            if ($k !== 'm' && !is_integer($v)) {
                return false;
            }
        }

        return true;
    }

    protected function guessStructuredRgbMode256($color)
    {
        $ok = $this->guessStructuredRgbModeCommon($color);

        if (!$ok) {
            return false;
        }

        return isset($color->m) && $color->m === 'rgb256';
    }


    protected function guessStructuredRgbModeTrueColor($color)
    {
        $ok = $this->guessStructuredRgbModeCommon($color);

        if (!$ok) {
            return false;
        }

        if (isset($color->m) && $color->m === 'rgb') {
            return true;
        }

        // si on n’a pas le mode explicitement, on a encore une chance de
        // l’avoir : si toutes les valeurs sont supérieures à 5, on peut en
        // déduire que c’est du True Colors
        if (!isset($color->m)) {
            foreach ($color as $k => $v) {
                if ($v <= 5) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    protected function doStringCase($color)
    {
        $this->value = self::$std_16_colors[$color];
        $this->mode = self::MODE_16_COLORS;
    }

    protected function doStringGrayscaleCase($color)
    {
        list($trash, $value) = explode('_', $color);
        $this->value = 231 + $value;
        $this->mode = self::MODE_256_GRAYSCALE;
    }

    protected function doIntegerRgbCase($color)
    {
        $this->value = $color;
        $this->mode = self::MODE_256_COLORS;
    }

    protected function doStructuredRgb256Case($color)
    {
        $this->value = 16 + 36 * $color->r + 6 * $color->g + $color->b;
        ;
        $this->mode = self::MODE_256_COLORS;
    }

    protected function doStructuredRgbTrueColorCase($color)
    {
        $this->value = $color;
        $this->mode = self::MODE_TRUE_COLORS;
    }

    public function choose($color)
    {
        $this->arrToObj($color);
        if ($this->guessString($color)) {
            $this->doStringCase($color);
        }

        if ($this->guessStringGrayscale($color)) {
            $this->doStringGrayscaleCase($color);
        }

        if ($this->guessIntegerRgbMode($color)) {
            $this->doIntegerRgbCase($color);
        }

        if ($this->guessStructuredRgbMode256($color)) {
            $this->doStructuredRgb256Case($color);
        }

        if ($this->guessStructuredRgbModeTrueColor($color)) {
            $this->doStructuredRgbTrueColorCase($color);
        }
    }

    public function isValid()
    {
        return !is_null($this->value);
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function is16Colors()
    {
        return $this->mode === self::MODE_16_COLORS;
    }

    public function is256Colors()
    {
        return $this->mode === self::MODE_256_COLORS;
    }

    public function isGrayscale()
    {
        return $this->mode === self::MODE_256_GRAYSCALE;
    }

    public function isTrueColors()
    {
        return $this->mode === self::MODE_TRUE_COLORS;
    }

    public function getCode()
    {
        return $this->value;
    }

    public function getAnsiCode()
    {
        return $this->value;
    }
}
