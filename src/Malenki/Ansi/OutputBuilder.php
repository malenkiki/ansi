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

class OutputBuilder
{
    const PATTERN_16_COLORS_FOREGROUND = "\033[%d;%d%dm";
    const PATTERN_16_COLORS_BACKGROUND = "\033[%d%dm";
    const PATTERN_256_COLORS           = "\033[%d;5;%dm";
    const PATTERN_TRUE_COLORS          = "\033[%d;2;%d;%d;%dm";
    const PATTERN_CLOSE_FORMAT         = "\033[2%dm";
    const PATTERN_RESET                = "\033[0m";

    protected $text;
    protected $layers = array();
    protected $effect;

    public function __construct($text = '')
    {
        $this->text = $text;
    }


    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function addLayer(Layer $layer, Color $color = null)
    {
        $this->layers[$layer->getCode()] = (object) array(
            'layer' => $layer,
            'color' => $color
        );

        return $this;
    }

    public function useEffect(Effect $effect)
    {
        $this->effect = $effect;
        return $this;
    }

    protected function buildStart16ColorsCase($lc, &$out)
    {
        if (!$lc->color->is16Colors()) {
            return;
        }

        if ($lc->layer->isForeground()) {
            $out[] = sprintf(
                self::PATTERN_16_COLORS_FOREGROUND,
                $this->effect->getAnsiCode(),
                $lc->layer->getAnsiCode($lc->color),
                $lc->color->getAnsiCode()
            );
        }

        if ($lc->layer->isBackground()) {
            $out[] = sprintf(
                self::PATTERN_16_COLORS_BACKGROUND,
                $lc->layer->getAnsiCode($lc->color),
                $lc->color->getAnsiCode()
            );
        }
    }

    protected function buildStart256ColorsOrGrayscaleCase($lc, &$out)
    {
        if (!($lc->color->is256Colors() || $lc->color->isGrayscale())) {
            return;
        }

        $out[] = sprintf(
            self::PATTERN_256_COLORS,
            $lc->layer->getAnsiCode($lc->color),
            $lc->color->getAnsiCode()
        );
    }


    protected function buildStartTrueColorsCase($lc, &$out)
    {
        if (!$lc->color->isTrueColors()) {
            return;
        }

        $oc = $lc->color->getAnsiCode();

        $out[] = sprintf(
            self::PATTERN_TRUE_COLORS,
            $lc->layer->getAnsiCode($lc->color),
            $oc->r,
            $oc->g,
            $oc->b
        );
    }

    protected function buildClose(&$out)
    {
        if ($this->effect->getAnsiCode()) {
            $out[] = sprintf(self::PATTERN_CLOSE_FORMAT, $this->effect->getAnsiCode());
        }
        $out[] = self::PATTERN_RESET;
    }


    public function build()
    {
        $out = array();

        foreach (Layer::getCodes() as $code) {
            if (!isset($this->layers[$code])) {
                continue;
            }
            $lc = $this->layers[$code];

            $this->buildStart16ColorsCase($lc, $out);
            $this->buildStart256ColorsOrGrayscaleCase($lc, $out);
            $this->buildStartTrueColorsCase($lc, $out);
        }

        $out[] = $this->text;

        $this->buildClose($out);

        return implode('', $out);
    }
}
