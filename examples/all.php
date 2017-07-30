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

(@include_once __DIR__ . '/../vendor/autoload.php') || @include_once __DIR__ . '/../../../autoload.php';

function displayTitle($title)
{
    $t = new Ansi(strtoupper($title));
    echo PHP_EOL;
    echo PHP_EOL;
    echo $t->bold->white;
    echo PHP_EOL;
}

function display16ColorsForegroundAndEffects()
{
    $names = Ansi\Color::getStandardNames();
    $effects = Ansi\Effect::getStandardNames();

    foreach ($names as $color) {
        $s = new Ansi($color);
        echo $s->fg($color);
        echo "\t";
        foreach ($effects as $effect) {
            echo $s->v("$color $effect")->fg($color)->$effect();
            echo "\t";
        }
        echo PHP_EOL;
    }
}

function display16ColorsBackgroundAndEffects()
{
    $names = Ansi\Color::getStandardNames();
    $effects = Ansi\Effect::getStandardNames();

    foreach ($names as $color) {
        $s = new Ansi($color);
        echo $s->fg('default');
        echo "\t";
        foreach ($effects as $effect) {
            echo $s->v("$color $effect")->bg($color)->$effect();
            echo "\t";
        }
        echo PHP_EOL;
    }
}
//for previous, you can use magic getters too:

function display16ColorsAsMagicGetters()
{
    $yeah = new Ansi('A lot of magic getters are available!');
    echo $yeah->red->bold->underline->bg_blue;
    echo "\n";

    $s = new Ansi();
    $s->bold->underline;

    foreach (array('black', 'red', 'green', 'yellow', 'blue', 'purple', 'cyan', 'white') as $color) {
        echo $s->v($color)->$color;
        echo " ";
    }

    echo PHP_EOL;
}

function displayTagParsing()
{
    echo new Ansi('You can <bold>parse <cyan>string</cyan></bold> containing <red>some tags</red> to <bg_magenta>have</bg_magenta> <underline><yellow>some effects</yellow> too</underline>!');
    echo "\n";
}

function display256ColorsForeground()
{
    $cc = new Ansi();
    // $cc->v('=');
    $range = range(0, 5);

    foreach ($range as $r) {
        foreach ($range as $g) {
            foreach ($range as $b) {
                echo '[';
                echo $cc->v("*")->fg([$r, $g, $b, 'rgb256']);
                echo "] ($r,$g,$b)";
                if ($b === 5) {
                    echo PHP_EOL;
                    echo PHP_EOL;
                } else {
                    echo " ";
                }
            }
        }
    }
    echo "\n";
}

function display256ColorsBackground()
{
    $cc = new Ansi();
    // $cc->v('=');
    $range = range(0, 5);

    foreach ($range as $r) {
        foreach ($range as $g) {
            foreach ($range as $b) {
                echo '[';
                echo $cc->v("*")->bg([$r, $g, $b, 'rgb256']);
                echo "] ($r,$g,$b)";
                if ($b === 5) {
                    echo PHP_EOL;
                    echo PHP_EOL;
                } else {
                    echo " ";
                }
            }
        }
    }
    echo "\n";
}

function displayGrayscale()
{
    $gc = new Ansi(' ');

    $range = range(1, 24);

    foreach ($range as $bw) {
        $value = "grayscale_$bw";
        printf('[%s] %s', $gc->bg($value), $value);

        /*
        $len = strlen($value) + 4;
        $padding = 20 - $len;
        echo str_pad('', $padding, ' ', STR_PAD_LEFT) . $cell
        */

        if ($bw % 3 === 0) {
            echo PHP_EOL;
        } else {
            if ($bw <= 9) {
                echo '     ';
            } else {
                echo '    ';
            }
        }
    }

    echo "\n";
}

function displayTrueColors()
{
    $range = range(0, 255, 5);
    $tc = new Ansi(' ');

    $i = 1;
    $g = 254/2;
    foreach ($range as $r) {
        foreach ($range as $b) {
            echo $tc->bg([$r,$g, $b, 'rgb']);

            if ($i === 52) {
                echo PHP_EOL;
                $i = 0;
            }
            $i++;
        }
    }
    echo PHP_EOL;
}

displayTitle('16 colors foreground using effects');
display16ColorsForegroundAndEffects();
displayTitle('16 colors background using effects');
display16ColorsBackgroundAndEffects();

displayTitle('16 colors using magic getters');
display16ColorsAsMagicGetters();

displayTitle('256 colors foreground');
display256ColorsForeground();
displayTitle('256 colors background');
display256ColorsBackground();

// TODO standard_XX and standard_bright_XX

displayTitle('Grayscale from 256 colors space');
displayGrayscale();

displayTitle('You can use string with tags to have multiple formats');
displayTagParsing();

displayTitle('Display true colors sample');
displayTrueColors();
