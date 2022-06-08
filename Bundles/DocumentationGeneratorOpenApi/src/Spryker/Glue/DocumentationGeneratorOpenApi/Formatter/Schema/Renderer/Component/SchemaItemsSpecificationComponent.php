<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component;

use Generated\Shared\Transfer\SchemaItemsComponentTransfer;

/**
 * Specification:
 *  - This component describes a single Schema Object Property.
 *  - This component partly covers Schema Object Properties in OpenAPI specification format (see https://swagger.io/specification/#schemaObject).
 */
class SchemaItemsSpecificationComponent implements SchemaItemsSpecificationComponentInterface
{
    /**
     * @var string
     */
    protected const KEY_REF = '$ref';

    /**
     * @var string
     */
    protected const KEY_ONEOF = 'oneOf';

    /**
     * @param \Generated\Shared\Transfer\SchemaItemsComponentTransfer $schemaItemsComponentTransfer
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(SchemaItemsComponentTransfer $schemaItemsComponentTransfer): array
    {
        $schemaItems = [];

        if ($schemaItemsComponentTransfer->getOneOf()) {
            foreach ($schemaItemsComponentTransfer->getOneOf() as $ref) {
                $schemaItems[static::KEY_ONEOF][][static::KEY_REF] = $ref;
            }
        }

        return $schemaItems;
    }
}
