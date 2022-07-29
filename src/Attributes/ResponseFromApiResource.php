<?php

namespace Knuckles\Scribe\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class ResponseFromApiResource
{
    public function __construct(
        public string  $name,
        public string  $model,
        public int     $status = 200,
        /* Mark if this should be used as a collection. Only needed if not using a ResourceCollection. */
        public bool    $collection = false,

        public ?string $description = '',
        public array   $factoryStates = [],
        public array   $with = [],

        public ?int    $paginate = null,
        public ?int    $simplePaginate = null,
        public array   $additionalData = [],
    )
    {
    }
}
