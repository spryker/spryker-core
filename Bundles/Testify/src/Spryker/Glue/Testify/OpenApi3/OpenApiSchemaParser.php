<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3;

use Spryker\Glue\Testify\OpenApi3\Reference\ReferenceResolver;
use Spryker\Glue\Testify\OpenApi3\SchemaObject\OpenApi;

class OpenApiSchemaParser implements OpenApiSchemaParserInterface
{
    /**
     * @param \Spryker\Glue\Testify\OpenApi3\ReaderInterface $reader
     *
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaObject\OpenApi
     */
    public function parse(ReaderInterface $reader): OpenApi
    {
        $document = new OpenApi();
        $referenceContainer = new ReferenceResolver($document);
        $mapper = new Mapper($referenceContainer);

        $mapper->mapObjectFromPayload(
            $document,
            $reader->read()
        );

        return $document;
    }
}
