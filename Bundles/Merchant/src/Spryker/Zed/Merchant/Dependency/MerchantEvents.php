<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Dependency;

class MerchantEvents
{
    /**
     * Specification
     * - This events will be used for spy_merchant publishing.
     *
     * @api
     */
    public const MERCHANT_PUBLISH = 'Merchant.merchant.publish';

    /**
     * Specification
     * - This events will be used for spy_merchant entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_UPDATE = 'Entity.spy_merchant.update';

    /**
     * Specification
     * - This events will be used for spy_merchant_store entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_STORE_CREATE = 'Entity.spy_merchant_store.create';

    /**
     * Specification
     * - This events will be used for spy_merchant_store entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_STORE_DELETE = 'Entity.spy_merchant_store.delete';

    /**
     * Specification
     * - This events will be used for spy_merchant_store publishing.
     *
     * @api
     */
    public const MERCHANT_STORE_PUBLISH = 'MerchantStore.spy_merchant_store.publish';
}
