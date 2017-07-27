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

class Effect
{
    protected $std_effects = array(
        'faint'     => 2,
        'bold'      => 1,
        'italic'    => 3,
        'underline' => 4
    );
    /**
     * Format code.
     *
     * Default to 0, no effect.
     *
     * @var integer
     * @access protected
     */
    protected $format = 0;

    protected function checkFormat($name)
    {
        if (!isset($this->std_effects[$name])) {
            throw new \InvalidArgumentException('Given format does not exist!');
        }
    }
    public function choose($name)
    {
        $this->checkFormat($name);
        $this->format = $this->std_effects[$name];
    }

    public function reset()
    {
        $this->format = 0;
    }

    public function getCode()
    {
        return $this->format;
    }
}
