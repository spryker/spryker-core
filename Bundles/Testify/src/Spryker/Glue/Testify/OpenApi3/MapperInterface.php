<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3;

use Spryker\Glue\Testify\OpenApi3\Collection\CollectionInterface;
use Spryker\Glue\Testify\OpenApi3\Object\ObjectInterface;
use Spryker\Glue\Testify\OpenApi3\Primitive\PrimitiveInterface;

interface MapperInterface
{
    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Object\ObjectInterface $object
     * @param mixed $payload
     *
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    public function mapObjectFromPayload(ObjectInterface $object, $payload): SchemaFieldInterface;

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Collection\CollectionInterface $collection
     * @param mixed $payload
     *
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    public function mapCollectionFromPayload(CollectionInterface $collection, $payload): SchemaFieldInterface;

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Primitive\PrimitiveInterface $primitive
     * @param mixed $payload
     *
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    public function mapPrimitiveFromPayload(PrimitiveInterface $primitive, $payload): SchemaFieldInterface;
}
