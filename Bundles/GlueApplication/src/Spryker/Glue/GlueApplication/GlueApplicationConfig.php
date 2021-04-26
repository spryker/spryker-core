<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;

class GlueApplicationConfig extends AbstractBundleConfig
{
    public const COLLECTION_IDENTIFIER_CURRENT_USER = 'mine';

    public const VALIDATE_REQUEST_HEADERS = true;

    /**
     * Specification:
     *  - Enables or disables request header validation.
     *
     * @api
     *
     * @return bool
     */
    public function getValidateRequestHeaders(): bool
    {
        return static::VALIDATE_REQUEST_HEADERS;
    }

    /**
     * Specification:
     *  - Domain name of glue application to build API links.
     *
     * @api
     *
     * @return string
     */
    public function getGlueDomainName(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN);
    }

    /**
     * Specification:
     *  - Indicates whether debug of rest is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function getIsRestDebugEnabled(): bool
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_REST_DEBUG, false);
    }

    /**
     * Specification:
     *  - Specifies a URI that may access the resources.
     *
     * @api
     *
     * @return string
     */
    public function getCorsAllowOrigin(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_CORS_ALLOW_ORIGIN, '');
    }

    /**
     * Specification:
     *  - List of allowed CORS headers.
     *
     * @api
     *
     * @return string[]
     */
    public function getCorsAllowedHeaders(): array
    {
        return [
            RequestConstantsInterface::HEADER_ACCEPT,
            RequestConstantsInterface::HEADER_CONTENT_TYPE,
            RequestConstantsInterface::HEADER_CONTENT_LANGUAGE,
            RequestConstantsInterface::HEADER_ACCEPT_LANGUAGE,
            RequestConstantsInterface::HEADER_AUTHORIZATION,
        ];
    }

    /**
     * Specification:
     *  - Indicates whether all relationships should be included in response by default.
     *
     * @api
     *
     * @return bool
     */
    public function isEagerRelationshipsLoadingEnabled(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(GlueApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
    }
}
