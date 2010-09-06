<?php

namespace Bundle\AWeekOfSymfonyBundle\Renderer;

use Bundle\AWeekOfSymfonyBundle\Model\Entry;

/**
 * Markdown renderer
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class MarkdownRenderer
{
    protected $formatter;
    protected $entry;

    public function __construct(Entry $entry)
    {
        $this->formatter = new MarkdownFormatter();
        $this->entry = $entry;
    }

    public function output()
    {
        $contents = array();
        $contents[] = $this->getSubject();

        $contents[] = $this->outputReportMessage();
        $contents[] = "<br />\n<br />\n<hr />";

        $contents[] = $this->formatter->linkText(rtrim($this->entry->getSummary()));

        if ($this->entry->hasMailingList()) {
            $contents[] = $this->formatter->header('開発メーリングリスト', 2);

            $links = array();
            foreach ($this->entry->getMailingList() as $thread) {
                $links[] = $this->formatter->link($thread->getSubject(), $thread->getUri());
            }
            $contents[] = $this->formatter->ulist($links);
        }

        if ($this->entry->hasHighlights()) {
            $contents[] = $this->formatter->header('開発ハイライト', 2);

            foreach ($this->entry->getAllHighlights() as $highlights) {
                $contents[] = $this->formatter->header(str_replace(array('branch', ' development highlights'), array('ブランチ', '開発ハイライト'), $highlights->getLabel()), 3);

                $contents[] = $this->formatter->link('チェンジログ', $highlights->getChangeLogUri()) . ':';

                $commits = array();
                foreach ($highlights as $h) {
                    $links = array();
                    foreach ($h->getCommits() as $label => $uri) {
                        $links[] = $this->formatter->link($label, $uri);
                    }
                    $commits[] =  implode(', ', $links). ' ' . htmlspecialchars_decode($h->getContent(), ENT_QUOTES);
                }

                $contents[] = $this->formatter->ulist($commits);

                if ($highlights->hasSummaries()) {
                    foreach ($highlights->getSummaries() as $summary) {
                        $contents[] = $this->formatter->linkText($summary);
                    }
                }
            }
        }

        $contents[] = $this->outputTranslatorComment();

        return implode("\n\n", $contents);
    }

    protected function getSubject()
    {
        return $this->formatter->header($this->entry->getSubject(), 1);
    }

    protected function outputReportMessage()
    {
        return sprintf(<<<EOS
Symfony公式ブログで毎週公開される、Symfony関連の活動まとめ記事の翻訳です。
この翻訳では、Symfony本体に関連したアップデートなどのみを取り上げます。
プラグインの更新等も含む全文は、以下のリンクからご確認ください。

（%s）
EOS
        , $this->formatter->link('原文リンク', $this->entry->getUri()));
    }

    protected function outputTranslatorComment()
    {
        $comment = <<<EOS
> **NOTE**
> 翻訳者コメント<br />
> 
EOS;

        if ($this->entry->hasTranslatorComment()) {
            $comment .= $this->entry->getTranslatorComment();
        }

        return $comment;
    }
}
