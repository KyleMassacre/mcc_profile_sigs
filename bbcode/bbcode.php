<?php

use Thunder\Shortcode\ShortcodeFacade;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class bbcode
{
    private ShortcodeFacade $engine;

    public function __construct()
    {
        $this->engine = new ShortcodeFacade();
        $codes = ["b", "i", "u", "s", "sub", "sup", "ul", "ol"];
        foreach ($codes as $code)
        {
            $this->engine->addHandler($code, function (ShortcodeInterface $s) use($code){
                return sprintf("<$code>%s</$code>", $s->getContent());
            });
        }

        $this->engine->addHandler("font", function(ShortcodeInterface $s) {
            $text = "<span style='";
            if($s->getParameter('size'))
            {
                $text .= sprintf("font-size:%spx;", math_clamp($s->getParameter('size'), 5, 20));
            }
            if($s->getParameter('color'))
            {
                $text .= sprintf("color:%s;", $s->getParameter('color'));
            }
            $text .= sprintf("'>%s</span>", $s->getContent());
            return $text;
        });
    }

    function bbcode()
    {

        //$this->engine->simple_bbcode_tag("big");
        //$this->engine->simple_bbcode_tag("small");
        //$this->engine->cust_tag("/\[ul\](.+?)\[\/ul\]/is",
        //        "<table><tr><td align='left'><ul>\\1</ul></td></tr></table>");
        //$this->engine->cust_tag("/\[ol\](.+?)\[\/ol\]/is",
        //        "<table><tr><td align='left'><ol>\\1</ol></td></tr></table>");
        //$this->engine->cust_tag("/\[list\](.+?)\[\/list\]/is",
        //        "<table><tr><td align='left'><ul>\\1</ul></td></tr></table>");
        //$this->engine->cust_tag("/\[olist\](.+?)\[\/olist\]/is",
        //        "<table><tr><td align='left'><ol>\\1</ol></td></tr></table>");
        //$this->engine->adv_bbcode_tag("item", "li");
        //$this->engine->adv_option_tag("font", "font", "face");
        //$this->engine->adv_option_tag("size", "font", "size");
        //$this->engine->adv_option_tag("url", "a", "href");
        //$this->engine->adv_option_tag("color", "font", "color");
        //$this->engine->adv_option_tag("style", "span", "style");
        //$this->engine->cust_tag("/\(c\)/", "&copy;");
        //$this->engine->cust_tag("/\(tm\)/", "&#153;");
        //$this->engine->cust_tag("/\(r\)/", "&reg;");
        //$this->engine->adv_option_tag_em("email", "a", "href");
        //$this->engine->adv_bbcode_att_em("email", "a", "href");
        //$this->engine->cust_tag("/\[left\](.+?)\[\/left\]/i",
        //        "<div align='left'>\\1</div>");
        //$this->engine->cust_tag("/\[center\](.+?)\[\/center\]/i",
        //        "<div align='center'>\\1</div>");
        //$this->engine->cust_tag("/\[right\](.+?)\[\/right\]/i",
        //        "<div align='right'>\\1</div>");
        //$this->engine->cust_tag("/\[quote=(.+?)\]/i",
        //        "<div class='quotetop'>QUOTE (\\1)</div><div class='quotemain'>");
        //$this->engine->cust_tag("/\[quote\]/i",
        //        "<div class='quotetop'>QUOTE</div><div class='quotemain'>");
        //$this->engine->cust_tag("/\[\/quote\]/i", "</div>");
        //$this->engine->cust_tag("/\[code\](.+?)\[\/code\]/i",
        //        "<div class='codetop'>CODE</div><div class='codemain'><code>\\1</code></div>");
        //$this->engine->cust_tag("/\[codebox\](.+?)\[\/codebox\]/i",
        //        "<div class='codetop'>CODE</div><div class='codemain' style='height:200px;white-space:pre;overflow:auto'>\\1</div>");
        //$this->engine->cust_tag("/\[img=(.+?)\]/ie", "check_image('\\1')");
        //$this->engine->cust_tag("/\[img](.+?)\[\/img\]/ie",
        //        "check_image('\\1')");
        //$this->engine->cust_tag("/&nbrlb;/", "<br />");
        //$this->engine->cust_tag("/\[userbox\]([0-9]+)\[\/userbox\]/ie",
        //        "userBox(\\1)");
        //$this->engine->cust_tag("/\[hr\]/is", "<hr />");
        //$this->engine->cust_tag("/\[\*\]/", "<li>");
    }

    function bbcode_parse($html = "")
    {
        $html = strip_tags($html);
        $mf = $this->engine->process($html);
        return $mf;
    }

    function quote_corrector($in)
    {
        $quotes = substr_count($in, "[/quote]");
        $quote_starts = substr_count($in, "[quote");
        if ($quote_starts > $quotes)
        {
            return $in . str_repeat("[/quote]", $quote_starts - $quotes);
        }
        elseif ($quotes > $quote_starts)
        {
            $so = 0;
            $poss = array();
            for ($i = 0; $i < $quotes; $i++)
            {
                $kx = strpos($in, "[/quote]", $so);
                $so = $kx;
                $poss[] = $kx;
            }
            while ($quotes > $quote_starts)
            {
                $num = $quotes - 1;
                $in =
                    substr($in, 0, $poss[$num])
                    . ($poss[$num] + 8 >= strlen($in) ? ""
                        : substr($in, $poss[$num] + 8));
                $quotes--;
            }
            return $in;
        }
        else
        {
            return $in;
        }
    }
}

$bbc = new bbcode;
