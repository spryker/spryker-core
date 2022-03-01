<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class GlueRestApiConventionConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - REST API convention identifier.
     *
     * @api
     *
     * @var string
     */
    public const CONVENTION_REST_API = 'rest_api';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_CODE_UNSUPPORTED_ACCEPT_FORMAT = '010';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_MESSAGE_UNSUPPORTED_ACCEPT_FORMAT_MESSAGE = 'Unsupported "Accept" format used.';

    /**
     * @var string
     */
    protected const DEFAULT_FORMAT = 'application/json';

    /**
     * Specification:
     * - Returns the default format REST API will use if none could be negotiated.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultFormat(): string
    {
        return static::DEFAULT_FORMAT;
    }
}
