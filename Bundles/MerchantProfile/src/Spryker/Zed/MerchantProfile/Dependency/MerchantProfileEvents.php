<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Dependency;

class MerchantProfileEvents
{
    /**
     * Specification
     * - This events will be used for spy_merchant_profile entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PROFILE_CREATE = 'Entity.spy_merchant_profile.create';

    /**
     * Specification
     * - This events will be used for spy_merchant_profile entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PROFILE_UPDATE = 'Entity.spy_merchant_profile.update';

    /**
     * Specification
     * - This events will be used for spy_merchant_profile entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PROFILE_DELETE = 'Entity.spy_merchant_profile.delete';

    /**
     * Specification
     * - This events will be used for spy_merchant_profile publishing.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PROFILE_PUBLISH = 'Entity.spy_merchant_profile.publish';

    /**
     * Specification
     * - This events will be used for spy_merchant_profile un-publishing.
     *
     * @api
     */
    public const ENTITY_SPY_MERCHANT_PROFILE_UNPUBLISH = 'Entity.spy_merchant_profile.unpublish';
}
