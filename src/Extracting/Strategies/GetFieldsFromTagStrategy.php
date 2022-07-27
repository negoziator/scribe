<?php

namespace Knuckles\Scribe\Extracting\Strategies;

use Knuckles\Scribe\Extracting\ParamHelpers;
use Knuckles\Scribe\Extracting\TagStrategyWithFormRequestFallback;
use Mpociot\Reflection\DocBlock\Tag;

abstract class GetFieldsFromTagStrategy extends TagStrategyWithFormRequestFallback
{
    use ParamHelpers;

    protected string $tagName = "";

    /**
     * @param Tag[] $tagsOnMethod
     * @param Tag[] $tagsOnClass
     *
     * @return array[]
     */
    public function getFromTags(array $tagsOnMethod, array $tagsOnClass = []): array
    {
        $fields = [];

        foreach ($tagsOnClass as $tag) {
            if (strtolower($tag->getName()) !== strtolower($this->tagName)) continue;

            $fieldData = $this->parseTag(trim($tag->getContent()));
            $fields[$fieldData['name']] = $fieldData;
        }

        foreach ($tagsOnMethod as $tag) {
            if (strtolower($tag->getName()) !== strtolower($this->tagName)) continue;

            $fieldData = $this->parseTag(trim($tag->getContent()));
            $fields[$fieldData['name']] = $fieldData;
        }

        return $fields;
    }

    abstract protected function parseTag(string $tagContent): array;

    protected function getDescriptionAndExample(string $description, string $type, string $tagContent): array
    {
        [$description, $example] = $this->parseExampleFromParamDescription($description, $type);
        $example = $this->setExampleIfNeeded($example, $type, $tagContent);
        return [$description, $example];
    }

    protected function setExampleIfNeeded(mixed $currentExample, string $type, string $tagContent): mixed
    {
        return (is_null($currentExample) && !$this->shouldExcludeExample($tagContent))
            ? $this->generateDummyValue($type)
            : $currentExample;
    }
}