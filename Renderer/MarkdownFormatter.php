<?php

namespace Bundle\AWeekOfSymfonyBundle\Renderer;

/**
 * Markdown formatter
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class MarkdownFormatter
{
    public function header($text, $level = 3)
    {
        if ($level <= 2) {
            $text .= "\n";
            $text .= str_repeat($level === 1 ? '=' : '-', strlen($text) / 3 * 2 + 2);
        } else {
            $text = str_repeat('#', $level) . ' ' . $text;
        }

        return $text;
    }

    public function ulist(array $list, $indent = 0, $marker = '-')
    {
        $results = array();
        foreach ($list as $line) {
            if (is_array($line)) {
                $a = $this->ulist($line, $indent + 1, $marker);
            } else {
                $a = str_repeat(' ', $indent * 2) . $marker . ' ' . $line;
            }

            $results[] = $a;
        }

        return implode("\n", $results);
    }

    public function link($title, $href)
    {
        if (!$href) {
            return $title;
        }

        return sprintf('[%s](%s)', $title, $href);
    }

    public function linkText($text)
    {
        $formatter = $this;

        return preg_replace_callback('!<a(?:[^>]*)href="([^"]+)"(?:[^>]*)>([^<]+)</a>!', function($matches) use($formatter) {
            return $formatter->link($matches[2], $matches[1]);
        }, $text);
    }
}
