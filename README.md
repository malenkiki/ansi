# Ansi

Use colors in PHP terminal apps!

You can use many **foregrounds**, **backgrounds** and **styles**.

Available **foreground** colors are: `black`, `red`, `green`, `yellow`, `blue`, `purple`, `cyan` and `white`.

Available **background** colors are:  `black`, `red`, `green`, `yellow`, `blue`, `magenta`, `cyan` and `gray`.

Available **styles** are: `faint`, `bold`, `italic` and `underline`, but this effects may appear in different way into some terminals.

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

You have examples into [examples directory](https://github.com/malenkiki/ansi/tree/master/examples) too.

All methods are chainable, except `render()`.


Some magic getters allow new syntax, so, all **foreground colors** are available by calling their name as attribute, it is also true for **faint**, **bold**, **italic** and **underline**. Example:

```php
echo (new Ansi('Hello World!'))->red->bold->underline;
```

Enjoy!
