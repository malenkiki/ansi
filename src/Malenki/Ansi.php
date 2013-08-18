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

class Ansi
{
    protected $str = null;

    protected $fg = null;
    protected $bg = null;
    protected $format = 0;


    protected static $arr_fg = array(
        'black'  => 30,
		'red'    => 31,
		'green'  => 32,
		'yellow' => 33,
		'blue'   => 34,
		'purple' => 35,
		'cyan'   => 36,
		'white'  => 37,
    );



    protected static $arr_bg = array(
        'black'   => 40,
		'red'     => 41,
		'green'   => 42,
		'yellow'  => 43,
		'blue'    => 44,
		'magenta' => 45,
		'cyan'    => 46,
		'gray'    => 47
    );



    public function __construct($str)
    {
        if(is_string($str) && strlen($str))
        {
            $this->str = $str;
        }
        else
        {
            throw new \Exception('Invalid string!');
        }
    }



    public function foreground($name)
    {
        if(array_key_exists($name, self::$arr_fg))
        {
            $this->fg = self::$arr_fg[$name];
        }
        else
        {
            throw \InvalidArgumentException(
                sprintf('Foreground color "%s" does not exist!', $name)
            );
        }

        return $this;
    }



    public function background($name)
    {
        if(array_key_exists($name, self::$arr_bg))
        {
            $this->bg = self::$arr_bg[$name];
        }
        else
        {
            throw \InvalidArgumentException(
                sprintf('Background color "%s" does not exist!', $name)
            );
        }

        return $this;
    }



    public function fg($name)
    {
        return $this->foreground($name);
    }



    public function bg($name)
    {
        return $this->background($name);
    }

    public function bold()
    {
        $this->format = 1;

        return $this;
    }

    public function faint()
    {
        $this->format = 2;

        return $this;
    }

    public function italic()
    {
        $this->format = 3;

        return $this;
    }

    public function underline()
    {
        $this->format = 4;

        return $this;
    }

    public function render()
    {
        $arr_out = array();

        if($this->fg)
        {
            $arr_out[] = sprintf("\033[%d;%dm", $this->format, $this->fg);
        }
        
        if($this->bg)
        {
            $arr_out[] = sprintf("\033[%dm", $this->bg);
        }

        $arr_out[] = $this->str;
        $arr_out[] = "\033[0m";

        return implode('', $arr_out);
    }



    public function __toString()
    {
        return $this->render();
    }
}
