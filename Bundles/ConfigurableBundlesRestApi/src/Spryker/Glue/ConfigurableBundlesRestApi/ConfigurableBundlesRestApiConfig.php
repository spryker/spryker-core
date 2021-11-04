<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ConfigurableBundlesRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATES = 'configurable-bundle-templates';

    /**
     * @var string
     */
    public const RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOTS = 'configurable-bundle-template-slots';

    /**
     * @var string
     */
    public const RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_SETS = 'configurable-bundle-template-image-sets';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = '3901';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = 'Configurable bundle template not found.';
}
