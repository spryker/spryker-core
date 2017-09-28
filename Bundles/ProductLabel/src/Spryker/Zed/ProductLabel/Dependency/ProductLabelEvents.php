<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency;

interface ProductLabelEvents
{

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_label_product_abstract.create';

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_label_product_abstract.update';

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_label_product_abstract.delete';

    /**
     * Specification
     * - This events will be used for spy_product_label entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_CREATE = 'Entity.spy_product_label.create';

    /**
     * Specification
     * - This events will be used for spy_product_label entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_UPDATE = 'Entity.spy_product_label.update';

    /**
     * Specification
     * - This events will be used for spy_product_label entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_DELETE = 'Entity.spy_product_label.delete';

    /**
     * Specification
     * - This events will be used for spy_product_label_localized_attributes entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE = 'Entity.spy_product_label_localized_attributes.create';

    /**
     * Specification
     * - This events will be used for spy_product_label_localized_attributes entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE = 'Entity.spy_product_label_localized_attributes.update';

    /**
     * Specification
     * - This events will be used for spy_product_label_localized_attributes entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_DELETE = 'Entity.spy_product_label_localized_attributes.delete';

}
