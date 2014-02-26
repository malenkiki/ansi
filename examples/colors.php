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

(@include_once __DIR__ . '/../vendor/autoload.php') || @include_once __DIR__ . '/../../../autoload.php';

foreach(array('black', 'red', 'green', 'yellow', 'blue', 'purple', 'cyan', 'white') as $color)
{
    //you can use `foreground` method too
    echo (new Ansi('Hello World!'))->fg($color);
    echo "\n";
}

echo (new Ansi('H'))->fg('black');
echo (new Ansi('e'))->fg('red');
echo (new Ansi('l'))->fg('green');
echo (new Ansi('l'))->fg('yellow');
echo (new Ansi('o'))->fg('blue');
echo (new Ansi(', '))->fg('purple');
echo (new Ansi('W'))->fg('cyan');
echo (new Ansi('o'))->fg('white');
echo (new Ansi('r'))->fg('black');
echo (new Ansi('l'))->fg('red');
echo (new Ansi('d'))->fg('green');
echo (new Ansi('!'))->fg('yellow');
echo "\n";

foreach(array('black', 'red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 'gray') as $color)
{
    //you can use `background` method too
    echo (new Ansi('Hello World!'))->bg($color);
    echo "\n";
}
