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
     * - This event will be used for product_abstract_image publishing
     *
     * @api
     */
    public const PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH = 'ProductImage.product_abstract_image.publish';

    /**
     * Specification
     * - This event will be used for product_abstract_image un-publishing
     *
     * @api
     */
    public const PRODUCT_IMAGE_PRODUCT_ABSTRACT_UNPUBLISH = 'ProductImage.product_abstract_image.unpublish';

    /**
     * Specification
     * - This event will be used for product_concrete_image publishing
     *
     * @api
     */
    public const PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH = 'ProductImage.product_concrete_image.publish';

    /**
     * Specification
     * - This event will be used for product_concrete_image un-publishing
     *
     * @api
     */
    public const PRODUCT_IMAGE_PRODUCT_CONCRETE_UNPUBLISH = 'ProductImage.product_concrete_image.unpublish';

    /**
     * Specification
     * - This event will be used for spy_product_image entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_CREATE = 'Entity.spy_product_image.create';

    /**
     * Specification
     * - This event will be used for spy_product_image entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_UPDATE = 'Entity.spy_product_image.update';

    /**
     * Specification
     * - This event will be used for spy_product_image entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_DELETE = 'Entity.spy_product_image.delete';

    /**
     * Specification
     * - This event will be used for spy_product_image_set entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE = 'Entity.spy_product_image_set.create';

    /**
     * Specification
     * - This event will be used for spy_product_image_set entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE = 'Entity.spy_product_image_set.update';

    /**
     * Specification
     * - This event will be used for spy_product_image_set entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE = 'Entity.spy_product_image_set.delete';

    /**
     * Specification
     * - This event will be used for spy_product_image_set_to_product_image entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE = 'Entity.spy_product_image_set_to_product_image.create';

    /**
     * Specification
     * - This event will be used for spy_product_image_set_to_product_image entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE = 'Entity.spy_product_image_set_to_product_image.update';

    /**
     * Specification
     * - This event will be used for spy_product_image_set_to_product_image entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE = 'Entity.spy_product_image_set_to_product_image.delete';
}
