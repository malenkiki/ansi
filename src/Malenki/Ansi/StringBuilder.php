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

class StringBuilder
{
    const PATTERN_16_COLORS_FOREGROUND = "\033[%d;%dm";
    const PATTERN_16_COLORS_BACKGROUND = "\033[%dm";
    const PATTERN_256_COLORS           = "\033[%d;5;%dm";
    const PATTERN_TRUE_COLORS          = "\033[%d;2;%d;%d;%dm"; // TODO
    const PATTERN_RESET                = "\033[0m";

    protected $text;
    protected $fgl;
    protected $bgl;

    public function __construct($text = '')
    {
        $this->text = $text;
    }

    public function setForegroundLayer(Layer $fgl)
    {
        $this->fgl = $fgl;
    }
    public function setBackgroundLayer(Layer $bgl)
    {
        $this->bgl = $bgl;
    }

}
