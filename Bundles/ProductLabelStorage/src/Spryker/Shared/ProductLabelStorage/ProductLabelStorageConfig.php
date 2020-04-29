<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductLabelStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductLabelStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification
     * - This events will be used for product_abstract_label publishing
     *
     * @api
     */
    public const PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH = 'ProductLabel.product_abstract_label.publish';

    /**
     * Specification
     * - This events will be used for product_abstract_label un-publishing
     *
     * @api
     */
    public const PRODUCT_LABEL_PRODUCT_ABSTRACT_UNPUBLISH = 'ProductLabel.product_abstract_label.unpublish';

    /**
     * Specification
     * - This events will be used for product_label_dictionary publishing
     *
     * @api
     */
    public const PRODUCT_LABEL_DICTIONARY_PUBLISH = 'ProductLabel.product_label_dictionary.publish';

    /**
     * Specification
     * - This events will be used for product_label_dictionary un-publishing
     *
     * @api
     */
    public const PRODUCT_LABEL_DICTIONARY_UNPUBLISH = 'ProductLabel.product_label_dictionary.unpublish';

    /**
     * Specification
     * - This events will be used for product_label_store publishing
     *
     * @api
     */
    public const PRODUCT_LABEL_STORE_PUBLISH = 'ProductLabel.product_label_store.publish';

    /**
     * Specification
     * - This events will be used for product_label_store un-publishing
     *
     * @api
     */
    public const PRODUCT_LABEL_STORE_UNPUBLISH = 'ProductLabel.product_label_store.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_label_product_abstract.create';

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_label_product_abstract.update';

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_label_product_abstract.delete';

    /**
     * Specification
     * - This events will be used for spy_product_label entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_CREATE = 'Entity.spy_product_label.create';

    /**
     * Specification
     * - This events will be used for spy_product_label entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_UPDATE = 'Entity.spy_product_label.update';

    /**
     * Specification
     * - This events will be used for spy_product_label entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_DELETE = 'Entity.spy_product_label.delete';

    /**
     * Specification
     * - This events will be used for spy_product_label_localized_attributes entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE = 'Entity.spy_product_label_localized_attributes.create';

    /**
     * Specification
     * - This events will be used for spy_product_label_localized_attributes entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE = 'Entity.spy_product_label_localized_attributes.update';

    /**
     * Specification
     * - This events will be used for spy_product_label_localized_attributes entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_DELETE = 'Entity.spy_product_label_localized_attributes.delete';

    /**
     * Specification
     * - This events will be used for spy_product_label_store entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_CREATE = 'Entity.spy_product_label_store.create';

    /**
     * Specification
     * - This events will be used for spy_product_label_store entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_UPDATE = 'Entity.spy_product_label_store.update';

    /**
     * Specification
     * - This events will be used for spy_product_label_store entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_DELETE = 'Entity.spy_product_label_store.delete';

    /**
     * Specification:
     * - Queue name as used for processing price messages.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_LABEL_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing price messages.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_LABEL_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_LABEL_RESOURCE_NAME = 'product_abstract_label';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_LABEL_DICTIONARY_RESOURCE_NAME = 'product_label_dictionary';
}
