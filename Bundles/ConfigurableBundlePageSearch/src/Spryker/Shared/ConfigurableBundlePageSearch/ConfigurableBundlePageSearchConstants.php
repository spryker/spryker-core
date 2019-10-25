<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ConfigurableBundlePageSearch;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ConfigurableBundlePageSearchConstants
{
    /**
     * Specification:
     * - Resource name, will be used for key generating
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME = 'configurable_bundle_template';

    /**
     * Specification:
     * - Queue name as used for processing Configurable Bundle Template messages
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_TEMPLATE_SEARCH_QUEUE = 'sync.search.configurable_bundle_template';

    /**
     * Specification:
     * - Queue name, used for processing Configurable Bundle Template messages
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_TEMPLATE_SEARCH_ERROR_QUEUE = 'sync.search.configurable_bundle_template.error';
}
