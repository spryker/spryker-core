<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOfferGui;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductOfferGuiConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_DENIED
     *
     * @var string
     */
    public const STATUS_DENIED = 'denied';
}
