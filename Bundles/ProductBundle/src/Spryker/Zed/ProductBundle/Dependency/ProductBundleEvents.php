<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Dependency;

class ProductBundleEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_bundle publishing.
     *
     * @api
     */
    public const PRODUCT_BUNDLE_PUBLISH = 'ProductBundle.product_bundle.publish';

    /**
     * Specification:
     * - This event is used for spy_product_bundle unpublishing.
     *
     * @api
     */
    public const PRODUCT_BUNDLE_UNPUBLISH = 'ProductBundle.product_bundle.unpublish';

    /**
     * Specification
     * - This event is used for spy_product_bundle entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_CREATE = 'Entity.spy_product_bundle.create';

    /**
     * Specification
     * - This events will be used for spy_product_bundle entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_UPDATE = 'Entity.spy_product_bundle.update';

    /**
     * Specification:
     * - This event is used for spy_product_bundle entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_BUNDLE_DELETE = 'Entity.spy_product_bundle.delete';
}
