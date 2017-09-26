<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency;

interface ProductLabelEvents
{

    const PRODUCT_LABEL_ABSTRACT_PUBLISH = 'ProductLabel.productAbstract.publish';
    const PRODUCT_LABEL_ABSTRACT_UNPUBLISH = 'ProductLabel.productAbstract.unpublish';

    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_label_product_abstract.create';
    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_label_product_abstract.update';
    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_label_product_abstract.delete';

    const ENTITY_SPY_PRODUCT_LABEL_CREATE = 'Entity.spy_product_label.create';
    const ENTITY_SPY_PRODUCT_LABEL_UPDATE = 'Entity.spy_product_label.update';
    const ENTITY_SPY_PRODUCT_LABEL_DELETE = 'Entity.spy_product_label.delete';

    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE = 'Entity.spy_product_label_localized_attributes.create';
    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE = 'Entity.spy_product_label_localized_attributes.update';
    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_DELETE = 'Entity.spy_product_label_localized_attributes.delete';

}
