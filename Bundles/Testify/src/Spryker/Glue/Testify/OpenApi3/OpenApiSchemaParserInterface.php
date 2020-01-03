<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3;

use Spryker\Glue\Testify\OpenApi3\SchemaObject\OpenApi;

interface OpenApiSchemaParserInterface
{
    /**
     * @param \Spryker\Glue\Testify\OpenApi3\ReaderInterface $reader
     *
     * @throws \Spryker\Glue\Testify\OpenApi3\Exception\ParseException
     *
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaObject\OpenApi
     */
    public function parse(ReaderInterface $reader): OpenApi;
}
