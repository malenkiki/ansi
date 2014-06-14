# Ansi

[![Latest Stable Version](https://poser.pugx.org/malenki/ansi/v/stable.svg)](https://packagist.org/packages/malenki/ansi) [![Total Downloads](https://poser.pugx.org/malenki/ansi/downloads.svg)](https://packagist.org/packages/malenki/ansi) [![Latest Unstable Version](https://poser.pugx.org/malenki/ansi/v/unstable.svg)](https://packagist.org/packages/malenki/ansi) [![License](https://poser.pugx.org/malenki/ansi/license.svg)](https://packagist.org/packages/malenki/ansi)

Use colors and styles in PHP terminal apps!

Quick example to understand:

```php
use Malenki\Ansi;

$a = new Ansi('Hello World!');
echo $a->red->bold->underline; // you get string in red color, bold and underline! :)
```

This was just little example, please read all this doc to see how to use Ansi! You can use metthod or magic getters, as you want, and chaining methods are available.

## Install It

You can get code here, from github by cloning this repository, or you can use [composer](https://getcomposer.org/) too. [Ansi is available on Packagist](https://packagist.org/packages/malenki/ansi)!

So, to install it using **composer**, just put something similar to the following lines into your own `composer.json` project file:

```json
{
    "require": {
        "malenki/ansi": "1.2.6",
    }
}
```

## Play With It

### What It can Do
You can use many **foregrounds**, **backgrounds** and **styles**:

 - Available **foreground** colors are: `black`, `red`, `green`, `yellow`, `blue`, `purple`, `cyan` and `white`.

 - Available **background** colors are:  `black`, `red`, `green`, `yellow`, `blue`, `magenta`, `cyan` and `gray`.

 - Available **styles** are: `faint`, `bold`, `italic` and `underline`, but this effects may appear in different way into some terminals.

Ansi can also **parse** a string containing special XML-like tags to format it.

Magic getters are available too, and many methods are chainable. Look at the [examples directory](https://github.com/malenkiki/ansi/tree/master/examples) or read next section to see how to use Ansi.

### Example Of Use

"Hello World!" in red:

``` php
use Malenki\Ansi;

$a = new Ansi('Hello World!');
echo $a->fg('red');
```

You can **set string after constructor call** using `v()` or `value()` methods, so you can keep color and formating and apply them for other strings:

```php
$a = new Ansi();
$a->fg('red');
echo $a->v('Hello World!'); //in red
echo $a->v('Hello World again!'); //in red too

```

"Hello World!" in red, background yellow:

``` php
use Malenki\Ansi;
echo (new Ansi('Hello World!'))->fg('red')->bg('yellow');
```

"Hello World!" in red, background yellow and in bold:

``` php
use Malenki\Ansi;
echo (new Ansi('Hello World!'))->fg('red')->bg('yellow')->bold();
```

You can use rendered string too:
``` php
use Malenki\Ansi;
$str = (new Ansi('Hello World!'))->fg('red')->bg('yellow')->bold()->render();
```

All methods are chainable, except `render()`.


Some magic getters allow new syntax, so, all **foreground colors** are available by calling their name as attribute, it is also true for **faint**, **bold**, **italic** and **underline**. Example:

```php
echo (new Ansi('Hello World!'))->red->bold->underline;
```

For **background colors**, you must preceed its name by `bg` like you can see into this example:

```php
echo (new Ansi('Hello World!'))->red->bold->underline->bg_blue;
```

More complex feature just added: parsing string having tags with same name as foreground colors and effects.

So an example to understand:

```php
echo Ansi::parse('You can <bold>parse <cyan>string</cyan></bold> containing <red>some tags</red> to have <underline><yellow>some effects</yellow></underline> too!');
```

Enjoy!

## MIT Open Source License

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
