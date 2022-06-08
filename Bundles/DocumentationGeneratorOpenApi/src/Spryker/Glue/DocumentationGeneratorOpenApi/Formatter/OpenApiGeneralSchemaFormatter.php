<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig;

class OpenApiGeneralSchemaFormatter implements OpenApiSchemaFormatterInterface
{
    /**
     * @var string
     */
    protected const OPENAPI_VERSION = '3.0.0';

    /**
     * @var string
     */
    protected const KEY_OPENAPI = 'openapi';

    /**
     * @var string
     */
    protected const KEY_INFO = 'info';

    /**
     * @var string
     */
    protected const KEY_VERSION = 'version';

    /**
     * @var string
     */
    protected const KEY_CONTACT = 'contact';

    /**
     * @var string
     */
    protected const KEY_CONTACT_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_CONTACT_URL = 'url';

    /**
     * @var string
     */
    protected const KEY_CONTACT_EMAIL = 'email';

    /**
     * @var string
     */
    protected const KEY_TITLE = 'title';

    /**
     * @var string
     */
    protected const KEY_LICENSE = 'license';

    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_SERVERS = 'servers';

    /**
     * @var string
     */
    protected const KEY_PATHS = 'paths';

    /**
     * @var string
     */
    protected const KEY_COMPONENTS = 'components';

    /**
     * @var string
     */
    protected const KEY_SCHEMAS = 'schemas';

    /**
     * @var string
     */
    protected const KEY_SECURITY_SCHEMES = 'securitySchemes';

    /**
     * @var string
     */
    protected const KEY_TAGS = 'tags';

    /**
     * @var string
     */
    protected const KEY_PARAMETERS = 'parameters';

    /**
     * @var string
     */
    protected const AUTH_SCHEMA_TYPE = 'http://';

    /**
     * @var string
     */
    protected const API_DOCUMENTATION_INFO_VERSION = '1.0.0';

    /**
     * @var string
     */
    protected const API_DOCUMENTATION_INFO_TITLE = 'Spryker API';

    /**
     * @var string
     */
    protected const API_DOCUMENTATION_INFO_LICENSE_NAME = 'MIT';

    /**
     * @var string
     */
    protected const API_DOCUMENTATION_CONTACT_NAME = 'Spryker';

    /**
     * @var string
     */
    protected const API_DOCUMENTATION_CONTACT_URL = 'https://support.spryker.com/';

    /**
     * @var string
     */
    protected const API_DOCUMENTATION_CONTACT_EMAIL = 'support@spryker.com';

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig
     */
    protected $documentationGeneratorApiConfig;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig $documentationGeneratorApiConfig
     */
    public function __construct(DocumentationGeneratorOpenApiConfig $documentationGeneratorApiConfig)
    {
        $this->documentationGeneratorApiConfig = $documentationGeneratorApiConfig;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(array $formattedData, ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array
    {
        return $formattedData + [
            static::KEY_OPENAPI => static::OPENAPI_VERSION,
            static::KEY_INFO => [
                static::KEY_VERSION => static::API_DOCUMENTATION_INFO_VERSION,
                static::KEY_CONTACT => [
                    static::KEY_CONTACT_NAME => static::API_DOCUMENTATION_CONTACT_NAME,
                    static::KEY_CONTACT_URL => static::API_DOCUMENTATION_CONTACT_URL,
                    static::KEY_CONTACT_EMAIL => static::API_DOCUMENTATION_CONTACT_EMAIL,
                ],
                static::KEY_TITLE => static::API_DOCUMENTATION_INFO_TITLE,
                static::KEY_LICENSE => [
                    static::KEY_NAME => static::API_DOCUMENTATION_INFO_LICENSE_NAME,
                ],
            ],
            static::KEY_TAGS => [],
            static::KEY_SERVERS => [
                [static::KEY_CONTACT_URL => static::AUTH_SCHEMA_TYPE . $apiApplicationSchemaContextTransfer->getHost()],
            ],
            static::KEY_PATHS => [],
            static::KEY_COMPONENTS => [
                static::KEY_SECURITY_SCHEMES => [],
                static::KEY_SCHEMAS => [],
                static::KEY_PARAMETERS => [],
            ],
        ];
    }
}
