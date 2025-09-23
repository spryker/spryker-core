<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class SelfServicePortalConfig extends AbstractBundleConfig
{
    /**
     * Specification
     * - Defines the collector resource name
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_SSP_ASSETS = 'ssp-assets';

    /**
     * Specification
     * - Defines the inquiries resource name
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_SSP_INQUIRIES = 'ssp-inquiries';

    /**
     * Specification
     * - Defines the services resource name
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_SSP_SERVICES = 'booked-services';
}
