<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency;

interface ProductEvents
{

    const PRODUCT_ABSTRACT_BEFORE_CREATE = 'Product.product_abstract.before.create';
    const PRODUCT_ABSTRACT_BEFORE_UPDATE = 'Product.product_abstract.before.update';

    const PRODUCT_CONCRETE_BEFORE_CREATE = 'Product.product_concrete.before.create';
    const PRODUCT_CONCRETE_BEFORE_UPDATE = 'Product.product_concrete.before.update';

    const PRODUCT_ABSTRACT_AFTER_UPDATE = 'Product.product_abstract.after.update';
    const PRODUCT_ABSTRACT_AFTER_CREATE = 'Product.product_abstract.after.create';

    const PRODUCT_CONCRETE_AFTER_CREATE = 'Product.product_concrete.after.create';
    const PRODUCT_CONCRETE_AFTER_UPDATE = 'Product.product_concrete.after.update';

    const PRODUCT_ABSTRACT_READ = 'Product.product_abstract.read';
    const PRODUCT_CONCRETE_READ = 'Product.product_concrete.read';

    const PRODUCT_ABSTRACT_PUBLISH = 'Product.product_abstract.publish';
    const PRODUCT_ABSTRACT_REFRESH = 'Product.product_abstract.refresh';
    const PRODUCT_ABSTRACT_UNPUBLISH = 'Product.product_abstract.unpublish';

    const PRODUCT_PUBLISH = 'Product.product.publish';
    const PRODUCT_REFRESH = 'Product.product.refresh';
    const PRODUCT_UNPUBLISH = 'Product.product.unpublish';

    const ENTITY_SPY_PRODUCT_CREATE = 'Entity.spy_product.create';
    const ENTITY_SPY_PRODUCT_UPDATE = 'Entity.spy_product.update';
    const ENTITY_SPY_PRODUCT_DELETE = 'Entity.spy_product.delete';

    const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE = 'Entity.spy_product_localized_attributes.create';
    const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE = 'Entity.spy_product_localized_attributes.update';
    const ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE = 'Entity.spy_product_localized_attributes.delete';

    const ENTITY_SPY_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_abstract.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_abstract.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_abstract.delete';

    const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_CREATE = 'Entity.spy_product_abstract_localized_attributes.create';
    const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_UPDATE = 'Entity.spy_product_abstract_localized_attributes.update';
    const ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_DELETE = 'Entity.spy_product_abstract_localized_attributes.delete';

    const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_CREATE = 'Entity.spy_product_attribute_key.create';
    const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_UPDATE = 'Entity.spy_product_attribute_key.update';
    const ENTITY_SPY_PRODUCT_ATTRIBUTE_KEY_DELETE = 'Entity.spy_product_attribute_key.delete';

}
