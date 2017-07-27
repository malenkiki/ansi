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

class Layer
{
    protected $codes = array(
        // TODO j’ai mal compris ça, à refaire
        'fg' => array(
            Color::MODE_16_COLORS     => 39,
            Color::MODE_256_COLORS    => 38,
            Color::MODE_TRUE_COLORS   => 38,
            Color::MODE_256_GRAYSCALE => 38
        ),
        'bg' => array(
            Color::MODE_16_COLORS     => 49,
            Color::MODE_256_COLORS    => 48,
            Color::MODE_TRUE_COLORS   => 48,
            Color::MODE_256_GRAYSCALE => 48
        )
    );

    protected $color;
    protected $effect;
    protected $value = 'fg';

    public function asForeground()
    {
        $this->choose('fg');
    }

    public function asBackground()
    {
        $this->choose('bg');
    }

    public function isForeground()
    {
        return $this->value === 'fg';
    }

    public function isBackground()
    {
        return $this->value === 'bg';
    }

    public function choose($code)
    {
        if (!in_array($code, $this->codes)) {
            throw new \InvalidArgumentException('This layer code does not exist!');
        }

        $this->value = $code;
    }

    public function setColor(Color $color)
    {
        $this->color = $color
        return $this;
    }

    public function setEffect(Effect $effect)
    {
        if ($this->isBackground()) {
            throw new \RuntimeException(
                'Effect cannot belongs to background layer'
            );
        }

        $this->effect = $effect;
        return $this;
    }


    public function getCode()
    {
        return $this->value;
    }

    public function getAnsiCode()
    {
        return $this->codes[$this->value][$this->color->getMode()];
    }
}
