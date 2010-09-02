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
        $contents[] = $this->getTitle();

        $contents[] = $this->outputReportMessage();
        $contents[] = "<br />\n<br />\n<hr />";

        $contents[] = $this->formatter->linkText(rtrim($this->entry->getSummary()));

        $contents[] = $this->formatter->header('開発メーリングリスト', 2);

        $links = array();
        foreach ($this->entry->getMailingList() as $uri => $label) {
            $links[] = $this->formatter->link($label, $uri);
        }
        $contents[] = $this->formatter->ulist($links);

        $contents[] = $this->formatter->header('開発ハイライト', 2);

        foreach ($this->entry->getAllHighlights() as $name => $highlights) {
            $contents[] = $this->formatter->header(str_replace('branch', 'ブランチ', $name), 3);

            $commits = array();
            foreach ($highlights as $h) {
                $links = array();
                foreach ($h->getCommits() as $label => $uri) {
                    $links[] = $this->formatter->link($label, $uri);
                }
                $commits[] =  implode(', ', $links). ' ' . htmlspecialchars_decode($h->getContent(), ENT_QUOTES);
            }

            $contents[] = $this->formatter->ulist($commits);
        }

        $contents[] = $this->formatter->link('その他多数', $this->entry->getOtherChangesUri());

        return implode("\n\n", $contents);
    }

    protected function getTitle()
    {
        return $this->formatter->header($this->entry->getTitle(), 1);
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
        return <<<EOS
> **NOTE**
> 翻訳者コメント<br />
> 
EOS;
    }
}
