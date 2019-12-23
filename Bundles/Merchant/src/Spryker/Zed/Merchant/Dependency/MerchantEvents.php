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
     * - This events will be used for spy_merchant entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_CREATE = 'Entity.spy_merchant.create';

    /**
     * Specification
     * - This events will be used for spy_merchant entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_UPDATE = 'Entity.spy_merchant.update';

    /**
     * Specification
     * - This events will be used for spy_merchant entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_DELETE = 'Entity.spy_merchant.delete';

    /**
     * Specification
     * - This events will be used for spy_merchant publishing.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PUBLISH = 'Entity.spy_merchant.publish';

    /**
     * Specification
     * - This events will be used for spy_merchant un-publishing.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_UNPUBLISH = 'Entity.spy_merchant.unpublish';
}
