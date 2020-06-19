<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Dependency;

interface MerchantProductEvents
{
    /**
     * Specification
     * - This events will be used for merchant product offer store key publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_ABSTRACT_KEY_PUBLISH = 'MerchantProductAbstract.key.publish';

    /**
     * Specification
     * - This events will be used for merchant product offer store key un-publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_ABSTRACT_KEY_UNPUBLISH = 'MerchantProductAbstract.key.unpublish';

    /**
     * Specification:
     * - Represents spy_merchant_product_abstract entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_merchant_product_abstract.create';

    /**
     * Specification:
     * - Represents spy_merchant_product_abstract entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_merchant_product_abstract.update';

    /**
     * Specification:
     * - Represents spy_merchant_product_abstract entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_merchant_product_abstract.delete';
}
