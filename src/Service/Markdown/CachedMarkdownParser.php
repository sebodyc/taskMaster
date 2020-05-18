<?php


namespace App\Service\Markdown;

use cebe\markdown\Parser;
use cebe\markdown\Markdown;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CachedMarkdownParser
{

    protected Parser $parser;
    protected CacheInterface $cache;

    public function __construct(Parser $parser, CacheInterface $cache)
    {
        $this->parser = $parser;
        $this->cache = $cache;
    }


    public function parse(string $markdown, int $expireAfter = 1200, string $cacheItemName = null): string
    {


        if (!$cacheItemName) {
            $cacheItemName = md5($markdown);
        }
        
        return $this->cache->get($cacheItemName, function (ItemInterface $item) use ($expireAfter, $markdown) {

            $item->expiresAfter($expireAfter);

            return $this->parser->parse($markdown);
        });
    }
}
