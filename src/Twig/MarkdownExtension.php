<?php

namespace App\Twig;

use App\Service\Markdown\CachedMarkdownParser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MarkdownExtension extends AbstractExtension
{

    protected CachedMarkdownParser $parser;

    public function __construct(CachedMarkdownParser $parser)
    {
        $this->parser = $parser;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('markdown', [$this, 'markdown'], [
                'is_safe' => ['html']
            ]),

        ];
    }

    public function markdown(string $markdown, int $expiresAfter = 1200)
    {


        return $this->parser->parse($markdown, $expiresAfter);
    }
}
