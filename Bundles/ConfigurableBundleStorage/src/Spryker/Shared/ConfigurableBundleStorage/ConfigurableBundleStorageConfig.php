<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ConfigurableBundleStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ConfigurableBundleStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Queue name as used for processing configurable bundles messages
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_SYNC_STORAGE_QUEUE = 'sync.storage.configurable_bundle';

    /**
     * Specification:
     * - Queue name as used for processing configurable bundles messages
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.configurable_bundle.error';

    /**
     * Specification:
     * - Key generation resource name of configurable bundles template.
     *
     * @api
     */
    public const CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME = 'configurable_bundle_template';
}
