<?php
namespace Phpsa\ContentToc;

use Illuminate\Support\Str;

class Parser
{

    protected $toc = [];
    protected $content = '';

    protected $slugs = [];

    public function __construct(string $content, int $level)
    {
        $this->process($content, $level);
    }

    protected function process(string $content, int $level)
    {
        $this->content = preg_replace_callback(
            '#<(h[1-' . $level . '])(.*?)>(.*?)</\1>#si',
            function ($matches) {
                $tag = $matches[1];
                $title = strip_tags($matches[3]);
                $hasId = preg_match('/id=(["\'])(.*?)\1[\s>]/si', $matches[2], $matchedIds);
                $id = $hasId ? $matchedIds[2] : $this->sluggify($title);

                $this->toc[] = "<div class='toc-item-$tag'><a href='#$id'>$title</a></div>";

                if ($hasId) {
                    return $matches[0];
                }
                return sprintf('<%s%s id="%s">%s</%s>', $tag, $matches[2], $id, $matches[3], $tag);
            },
            $content
        );
    }

    protected function sluggify(string $string): string
    {
        $clean = $original = Str::slug($string);
        $count = 1;
        while (in_array($clean, $this->slugs)) {
            $clean = $original . '-' . $count;
            $count++;
        }

        $this->slugs[] = $clean;
        return $clean;
    }

    public function getToc()
    {
        if (empty($this->toc)) {
            return null;
        }

        $html =  '<div class="table-of-contents">';
        $html .= implode("\n", $this->toc);

        $html .= '</div>';

        return $html;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function toArray()
    {
        return [
            'toc'     => $this->getToc(),
            'content' => $this->getContent()
        ];
    }
}
