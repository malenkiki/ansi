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

use Malenki\Ansi;

class TagFormat
{

    protected function parseDom($dom, &$str_out)
    {
        if ($dom->childNodes) {

            for ($i = 0; $i < $dom->childNodes->length; $i++) {
                $nodes = $dom->childNodes->item($i);

                //var_dump('Node found: '.$dom->localName);
                $this->parseDom($nodes, $str_out);
            }

        } else {
            $arr_tag_names = explode('/', trim($dom->getNodePath(),'/'));
            array_shift($arr_tag_names);
            array_pop($arr_tag_names);
            $a = new Ansi($dom->nodeValue);

            $arr_bg = Color::getStandardNames();

            $arr_effects = $arr_bg;
            $arr_effects = array_merge($arr_effects, Effect::getStandardNames());

            foreach ($arr_tag_names as $effect) {
                if (in_array($effect, $arr_effects)) {
                    $a->$effect;
                } elseif (preg_match('/^bg_/', $effect)) {
                    $effectbg = preg_replace('/^bg_/', '', $effect);

                    if (in_array($effectbg, $arr_bg)) {
                        $a->bg($effectbg);
                    }
                }
            }

            $str_out .= $a;
        }
    }

    protected function hasDomExtension()
    {
        if (!extension_loaded('dom')) {
            trigger_error(
                'DOM extension is not available! It is needed to parse string with tags!',
                E_USER_WARNING
            );

            return false;
        }

        return true;
    }

    public function hasTags($str)
    {
        return (boolean) preg_match("/\<.+\>.+\<\/.+\>/U",$str);
    }

    public function parse($str)
    {
        if (!$this->hasDomExtension()) {
            return strip_tags($str);
        }

        $dom = new \DOMDocument('1.0');

        if (!$dom->loadXML('<doc>'.$str.'</doc>')) {
            throw new \InvalidArgumentException('Your string has malformed tags!');
        }

        $str_out = '';

        $this->parseDom($dom, $str_out);

        return $str_out;
    }

}
