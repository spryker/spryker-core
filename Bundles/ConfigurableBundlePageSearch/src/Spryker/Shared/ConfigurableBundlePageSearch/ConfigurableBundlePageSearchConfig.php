<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ConfigurableBundlePageSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ConfigurableBundlePageSearchConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name, used for key generating.
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME = 'configurable_bundle_template';

    /**
     * Specification:
     * - Queue name, used for processing Configurable Bundle messages.
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_SEARCH_QUEUE = 'sync.search.configurable_bundle';

    /**
     * Specification:
     * - Queue name, used for processing Configurable Bundle messages.
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_SEARCH_ERROR_QUEUE = 'sync.search.configurable_bundle.error';
}
