<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOffer;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductOfferConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    public const STATUS_DENIED = 'denied';
}
