<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\ProductOfferGui\Communication\Controller\EditController::updateActivityStatusAction()
     */
    public const URL_UPDATE_APPROVAL_STATUS = '/product-offer-gui/edit/update-activity-status';
}
