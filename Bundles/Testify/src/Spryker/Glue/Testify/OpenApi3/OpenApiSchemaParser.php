<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3;

use Spryker\Glue\Testify\OpenApi3\Object\OpenApi;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferenceResolver;

class OpenApiSchemaParser implements OpenApiSchemaParserInterface
{
    /**
     * @param \Spryker\Glue\Testify\OpenApi3\ReaderInterface $reader
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Object\OpenApi
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
