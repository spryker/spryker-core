<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3;

use Spryker\Glue\Testify\OpenApi3\Object\OpenApi;

interface OpenApiSchemaParserInterface
{
    /**
     * @param \Spryker\Glue\Testify\OpenApi3\ReaderInterface $reader
     *
     * @throws \Spryker\Glue\Testify\OpenApi3\Exception\ParseException
     *
     * @return \Spryker\Glue\Testify\OpenApi3\Object\OpenApi
     */
    public function parse(ReaderInterface $reader): OpenApi;
}
