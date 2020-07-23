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
     * @uses \Spryker\Zed\ProductOfferGui\Communication\Controller\ProductOfferController::indexAction()
     */
    public const URL_PRODUCT_OFFER_LIST = '/product-offer-gui/list-product-offer';
}
