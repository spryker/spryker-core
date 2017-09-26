<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Dependency;

interface ProductImageEvents
{

    const PRODUCT_IMAGE_ABSTRACT_PUBLISH = 'ProductImage.productAbstract.publish';
    const PRODUCT_IMAGE_ABSTRACT_UNPUBLISH = 'ProductImage.productAbstract.unpublish';

    const PRODUCT_IMAGE_PRODUCT_PUBLISH = 'ProductImage.product.publish';
    const PRODUCT_IMAGE_PRODUCT_UNPUBLISH = 'ProductImage.product.unpublish';

    const ENTITY_SPY_PRODUCT_IMAGE_CREATE = 'Entity.spy_product_image.create';
    const ENTITY_SPY_PRODUCT_IMAGE_UPDATE = 'Entity.spy_product_image.update';
    const ENTITY_SPY_PRODUCT_IMAGE_DELETE = 'Entity.spy_product_image.delete';

    const ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE = 'Entity.spy_product_image_set.create';
    const ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE = 'Entity.spy_product_image_set.update';
    const ENTITY_SPY_PRODUCT_IMAGE_SET_DELETE = 'Entity.spy_product_image_set.delete';

    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE = 'Entity.spy_product_image_set_to_product_image.create';
    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE = 'Entity.spy_product_image_set_to_product_image.update';
    const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_DELETE = 'Entity.spy_product_image_set_to_product_image.delete';

}
