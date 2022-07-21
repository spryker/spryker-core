<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class OauthApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @uses \Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface::HEADER_AUTHORIZATION
     *
     * @var string
     */
    public const HEADER_AUTHORIZATION = 'authorization';

    /**
     * @api
     *
     * @uses \Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface::ATTRIBUTE_IS_PROTECTED
     *
     * @var string
     */
    public const REQUEST_ATTRIBUTE_IS_PROTECTED = 'is-protected';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_MISSING_ACCESS_TOKEN = 'Missing access token.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_INVALID_ACCESS_TOKEN = 'Invalid access token.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_ACCESS_CODE_INVALID = '001';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_FORBIDDEN = '002';

    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_TOKEN = 'token';
}
