<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Reference;

use Spryker\Glue\Testify\OpenApi3\Collection\AbstractCollection;
use Spryker\Glue\Testify\OpenApi3\Primitive\Any;
use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;
use Spryker\Glue\Testify\OpenApi3\SchemaObject\AbstractObject;

class ReferenceResolver implements ReferenceResolverInterface
{
    /**
     * @var \Spryker\Glue\Testify\OpenApi3\SchemaObject\AbstractObject[]
     */
    protected $containers = [];

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\SchemaObject\AbstractObject $container
     */
    public function __construct(AbstractObject $container)
    {
        $this->containers[''] = $container;
    }

    /**
     * @param string $reference
     *
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    public function resolveReference(string $reference): SchemaFieldInterface
    {
        [$file, $path] = explode('#', $reference, 2);
        $path = array_reverse(explode('/', trim($path, '/')));

        /** @var \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface $pointer */
        $pointer = $this->containers[$file] ?? new Any();

        while (count($path) > 0) {
            $segment = array_pop($path);

            if ($pointer instanceof AbstractObject && isset($pointer->{$segment})) {
                $pointer = $pointer->{$segment};

                continue;
            }

            if ($pointer instanceof AbstractCollection && $pointer->offsetExists($segment)) {
                $pointer = $pointer[$segment];

                continue;
            }

            return new Any();
        }

        return $pointer;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'containers' => array_keys($this->containers),
        ];
    }
}
