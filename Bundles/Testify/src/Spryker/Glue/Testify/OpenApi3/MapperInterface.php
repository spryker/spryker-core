<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3;

use Spryker\Glue\Testify\OpenApi3\Collection\CollectionInterface;
use Spryker\Glue\Testify\OpenApi3\Primitive\PrimitiveInterface;
use Spryker\Glue\Testify\OpenApi3\SchemaObject\ObjectInterface;

interface MapperInterface
{
    /**
     * @param \Spryker\Glue\Testify\OpenApi3\SchemaObject\ObjectInterface $object
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
