<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductConfigurationStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductConfigurationStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Queue name as used for processing product configuration messages
     *
     * @api
     */
    public const PRODUCT_CONFIGURATION_SYNC_STORAGE_QUEUE = 'sync.storage.product_configuration';

    /**
     * Specification:
     * - Queue name as used for processing product configuration error messages
     *
     * @api
     */
    public const PRODUCT_CONFIGURATION_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_configuration.error';

    /**
     * Specification:
     * - Resource name for product configuration.
     *
     * @api
     */
    public const PRODUCT_CONFIGURATION_RESOURCE_NAME = 'product_configuration';

    /**
     * Specification
     * - This events will be used for spy_product_configuration publishing
     *
     * @api
     */
    public const PRODUCT_CONFIGURATION_PUBLISH = 'Entity.spy_product_configuration.publish';

    /**
     * Specification
     * - This events will be used for spy_product_configuration un-publishing
     *
     * @api
     */
    public const PRODUCT_CONFIGURATION_UNPUBLISH = 'Entity.spy_product_configuration.unpublish';
    /**
     * Specification
     * - This events will be used for spy_product_configuration entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CONFIGURATION_CREATE = 'Entity.spy_product_configuration.create';

    /**
     * Specification
     * - This events will be used for spy_product_configuration entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CONFIGURATION_UPDATE = 'Entity.spy_product_configuration.update';

    /**
     * Specification
     * - This events will be used for spy_product_configuration entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_CONFIGURATION_DELETE = 'Entity.spy_product_configuration.delete';
}
