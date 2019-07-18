<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Dependency;

interface CategoryImageEvents
{
    /**
     * Specification
     * - This event will be used for category_image publishing
     *
     * @api
     */
    public const CATEGORY_IMAGE_CATEGORY_PUBLISH = 'CategoryImage.category_image.publish';

    /**
     * Specification
     * - This event will be used for category_image un-publishing
     *
     * @api
     */
    public const CATEGORY_IMAGE_CATEGORY_UNPUBLISH = 'CategoryImage.category_image.unpublish';

    /**
     * Specification
     * - This event will be used for spy_category_image entity changes
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_IMAGE_UPDATE = 'Entity.spy_category_image.update';

    /**
     * Specification
     * - This event will be used for spy_category_image_set entity creation
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_IMAGE_SET_CREATE = 'Entity.spy_category_image_set.create';

    /**
     * Specification
     * - This event will be used for spy_category_image_set entity changes
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_IMAGE_SET_UPDATE = 'Entity.spy_category_image_set.update';

    /**
     * Specification
     * - This event will be used for spy_category_image_set entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_IMAGE_SET_DELETE = 'Entity.spy_category_image_set.delete';

    /**
     * Specification
     * - This event will be used for spy_category_image_set_to_category_image entity creation
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE_CREATE = 'Entity.spy_category_image_set_to_category_image.create';

    /**
     * Specification
     * - This event will be used for spy_category_image_set_to_category_image entity changes
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE_UPDATE = 'Entity.spy_category_image_set_to_category_image.update';

    /**
     * Specification
     * - This event will be used for spy_category_image_set_to_category_image entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE_DELETE = 'Entity.spy_category_image_set_to_category_image.delete';
}
