<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductLabel;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductLabelConfig extends AbstractBundleConfig
{
    /**
     * This events will be used for product_abstract_label publishing
     */
    public const PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH = 'ProductLabel.product_abstract_label.publish';

    /**
     * This events will be used for product_abstract_label un-publishing
     */
    public const PRODUCT_LABEL_PRODUCT_ABSTRACT_UNPUBLISH = 'ProductLabel.product_abstract_label.unpublish';

    /**
     * This events will be used for product_label_dictionary publishing
     */
    public const PRODUCT_LABEL_DICTIONARY_PUBLISH = 'ProductLabel.product_label_dictionary.publish';

    /**
     * This events will be used for product_label_dictionary un-publishing
     */
    public const PRODUCT_LABEL_DICTIONARY_UNPUBLISH = 'ProductLabel.product_label_dictionary.unpublish';

    /**
     * This events will be used for spy_product_label_product_abstract entity creation
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_label_product_abstract.create';

    /**
     * This events will be used for spy_product_label_product_abstract entity changes
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_label_product_abstract.update';

    /**
     * This events will be used for spy_product_label_product_abstract entity deletion
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_label_product_abstract.delete';

    /**
     * This events will be used for spy_product_label entity creation
     */
    public const ENTITY_SPY_PRODUCT_LABEL_CREATE = 'Entity.spy_product_label.create';

    /**
     * This events will be used for spy_product_label entity changes
     */
    public const ENTITY_SPY_PRODUCT_LABEL_UPDATE = 'Entity.spy_product_label.update';

    /**
     * This events will be used for spy_product_label entity deletion
     */
    public const ENTITY_SPY_PRODUCT_LABEL_DELETE = 'Entity.spy_product_label.delete';

    /**
     * This events will be used for spy_product_label_localized_attributes entity creation
     */
    public const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE = 'Entity.spy_product_label_localized_attributes.create';

    /**
     * This events will be used for spy_product_label_localized_attributes entity changes
     */
    public const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE = 'Entity.spy_product_label_localized_attributes.update';

    /**
     * This events will be used for spy_product_label_localized_attributes entity deletion
     */
    public const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_DELETE = 'Entity.spy_product_label_localized_attributes.delete';

    /**
     * This events will be used for spy_product_label_store entity creation
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_CREATE = 'Entity.spy_product_label_store.create';

    /**
     * This events will be used for spy_product_label_store entity changes
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_UPDATE = 'Entity.spy_product_label_store.update';

    /**
     * This events will be used for spy_product_label_store entity deletion
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_DELETE = 'Entity.spy_product_label_store.delete';
}
