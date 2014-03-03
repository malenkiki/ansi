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
    $s = new Ansi('Hello World!');
    echo $s->fg($color);
    echo ' ';
    echo $s->fg($color)->faint();
    echo ' ';
    echo $s->fg($color)->bold();
    echo ' ';
    echo $s->fg($color)->italic();
    echo ' ';
    echo $s->fg($color)->underline();
    echo "\n";
}

//for previous, you can use magic getters too:

$yeah = new Ansi('A lot of magic getters are available!');
echo $yeah->red->bold->underline->bg_blue;
echo "\n";

foreach(array('black', 'red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 'gray') as $color)
{
    $s = new Ansi('Hello World!');
    echo $s->bg($color);
    echo ' ';
    echo $s->bg($color)->faint();
    echo ' ';
    echo $s->bg($color)->bold();
    echo ' ';
    echo $s->bg($color)->italic();
    echo ' ';
    echo $s->bg($color)->underline();
    echo "\n";
}

$s = new Ansi();
$s->bold->underline;

foreach(array('black', 'red', 'green', 'yellow', 'blue', 'purple', 'cyan', 'white') as $color)
{
    echo $s->v($color)->$color;
    echo "\n";
}


echo Ansi::parse('You can <bold>parse <cyan>string</cyan></bold> containing <red>some tags</red> to <bg_magenta>have</bg_magenta> <underline><yellow>some effects</yellow></underline> too!');
echo "\n";

