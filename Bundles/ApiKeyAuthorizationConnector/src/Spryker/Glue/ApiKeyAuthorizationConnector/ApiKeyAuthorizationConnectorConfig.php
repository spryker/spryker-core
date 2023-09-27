<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ApiKeyAuthorizationConnector;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ApiKeyAuthorizationConnectorConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const API_KEY_REQUEST_HEADER_NAME = 'x-api-key';

    /**
     * @var string
     */
    protected const API_KEY_REQUEST_PARAM_NAME = 'api_key';

    /**
     * Specification:
     * - Returns the name of the request header that contains the API key.
     *
     * @api
     *
     * @return string
     */
    public function getApiKeyRequestHeaderName(): string
    {
        return static::API_KEY_REQUEST_HEADER_NAME;
    }

    /**
     * Specification:
     * - Returns the name of the request parameter that contains the API key.
     *
     * @api
     *
     * @return string
     */
    public function getApiKeyRequestParamName(): string
    {
        return static::API_KEY_REQUEST_PARAM_NAME;
    }
}
