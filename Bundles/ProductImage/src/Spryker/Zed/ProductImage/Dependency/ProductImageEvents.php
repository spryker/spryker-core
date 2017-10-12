<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Dependency;

interface ProductImageEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_image entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_CREATE = 'Entity.spy_product_image.create';

    /**
     * Specification
     * - This events will be used for spy_product_image entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_UPDATE = 'Entity.spy_product_image.update';

    /**
     * Specification
     * - This events will be used for spy_product_image entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_DELETE = 'Entity.spy_product_image.delete';

    /**
     * Specification
     * - This events will be used for spy_product_image_set entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE = 'Entity.spy_product_image_set.create';

    /**
     * Specification
     * - This events will be used for spy_product_image_set entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE = 'Entity.spy_product_image_set.update';

    /**
     * Specification
     * - This events will be used for spy_product_image_set entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE = 'Entity.spy_product_image_set.delete';

    /**
     * Specification
     * - This events will be used for spy_product_image_set_to_product_image entity creation
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE = 'Entity.spy_product_image_set_to_product_image.create';

    /**
     * Specification
     * - This events will be used for spy_product_image_set_to_product_image entity changes
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE = 'Entity.spy_product_image_set_to_product_image.update';

    /**
     * Specification
     * - This events will be used for spy_product_image_set_to_product_image entity deletion
     *
     * @api
     */
    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE = 'Entity.spy_product_image_set_to_product_image.delete';
}
