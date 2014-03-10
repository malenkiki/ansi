# Ansi

Use colors and styles in PHP terminal apps!

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

More complex feature just added: parsing string having tags with same name as foreground colors and effects.

So an example to understand:

```php
echo Ansi::parse('You can <bold>parse <cyan>string</cyan></bold> containing <red>some tags</red> to have <underline><yellow>some effects</yellow></underline> too!');
```

Enjoy!
