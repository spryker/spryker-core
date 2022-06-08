<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;

class OpenApiSecuritySchemesFormatter implements OpenApiSchemaFormatterInterface
{
    /**
     * @var string
     */
    protected const BEARER_AUTH_TYPE = 'http';

    /**
     * @var string
     */
    protected const BEARER_AUTH_SCHEME = 'bearer';

    /**
     * @var string
     */
    protected const KEY_BEARER_AUTH = 'BearerAuth';

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(array $formattedData, ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array
    {
        $formattedData['components']['securitySchemes'] = [
            static::KEY_BEARER_AUTH => [
                'type' => static::BEARER_AUTH_TYPE,
                'scheme' => static::BEARER_AUTH_SCHEME,
            ],
        ];

        return $formattedData;
    }
}
