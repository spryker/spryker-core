<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ConfigurableBundleCartsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CONFIGURED_BUNDLES = 'configured-bundles';
    public const RESOURCE_GUEST_CONFIGURED_BUNDLES = 'guest-configured-bundles';
}
