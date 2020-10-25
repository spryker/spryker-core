<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MerchantSearchConfig extends AbstractBundleConfig
{
    /**
     * Defines resource name, that will be used for key generation.
     */
    public const MERCHANT_RESOURCE_NAME = 'merchant';

    /**
     * Defines queue name as used for processing translation messages.
     */
    public const SYNC_SEARCH_MERCHANT = 'sync.search.merchant';

    /**
     * This events that will be used for key writing.
     */
    public const MERCHANT_PUBLISH = 'Merchant.merchant.publish';

    /**
     * This events that will be used for key deleting.
     */
    public const MERCHANT_PUBLISH_DELETE = 'Merchant.publish_delete';

    /**
     * This events will be used for spy_merchant entity creation.
     */
    public const ENTITY_SPY_MERCHANT_CREATE = 'Entity.spy_merchant.create';

    /**
     * This events will be used for spy_merchant entity changes.
     */
    public const ENTITY_SPY_MERCHANT_UPDATE = 'Entity.spy_merchant.update';

    /**
     * This events will be used for spy_merchant entity deletion.
     */
    public const ENTITY_SPY_MERCHANT_DELETE = 'Entity.spy_merchant.delete';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     */
    public const MERCHANT_STATUS_APPROVED = 'approved';
}
