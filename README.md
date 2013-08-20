ansi
====

Use colors in PHP terminal apps!

You can use many foregrounds, backgrounds and styles.

Available foreground colors are: `black`, `red`, `green`, `yellow`, `blue`, `purple`, `cyan` and `white`.

Available background colors are:  `black`, `red`, `green`, `yellow`, `blue`, `magenta`, `cyan` and `gray`.

Available styles are: `faint`, `bold`, `italic` and `underline`, but this effects may appear in different way into some terminals.

"Hello World!" in red:

``` php
use Malenki\Ansi;
echo (new Ansi('Hello World!'))->fg('red');
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

Enjoy!
