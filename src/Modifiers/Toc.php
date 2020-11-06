<?php

namespace Phpsa\ContentToc\Modifiers;

use Phpsa\ContentToc\Parser;
use Statamic\Modifiers\Modifier;

class Toc extends Modifier
{

    protected $anchors = [];

    protected static $parsedContent = null;
    protected static $parsedToc = null;

    /**
     * Modify a value
     *
     * @param  mixed $value   The value to be modified
     * @param  array $params  Any parameters used in the modifier
     * @return mixed
     */
    public function index($value, $params)
    {

        $creatingIds = array_get($params, 0) == 'ids';
        $level = $creatingIds ? array_get($params, 1) ?? 2 : array_get($params, 0) ?? 2;

        if (static::$parsedContent === null) {
            $parser = new Parser($value, $level);
            static::$parsedContent = $parser->getContent();
            static::$parsedToc = $parser->getToc();
        }

        return $creatingIds ? static::$parsedContent : static::$parsedToc;
    }
}
