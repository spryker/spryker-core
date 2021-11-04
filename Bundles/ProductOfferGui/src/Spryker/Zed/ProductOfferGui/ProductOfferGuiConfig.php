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
     * @var string
     */
    public const REQUEST_PARAM_ID_PRODUCT_OFFER = 'id-product-offer';

    /**
     * @var string
     */
    public const REQUEST_PARAM_ID_PRODUCT_CONCRETE = 'id-product-concrete';

    /**
     * @var string
     */
    public const REQUEST_PARAM_APPROVAL_STATUS = 'approval-status';

    /**
     * @uses \Spryker\Zed\ProductOfferGui\Communication\Controller\ProductOfferController::indexAction()
     *
     * @var string
     */
    public const URL_PRODUCT_OFFER_LIST = '/product-offer-gui/list-product-offer';

    /**
     * @uses \Spryker\Zed\ProductOfferGui\Communication\Controller\EditController::updateApprovalStatusAction()
     *
     * @var string
     */
    public const URL_UPDATE_APPROVAL_STATUS = '/product-offer-gui/edit/update-approval-status';

    /**
     * @uses \Spryker\Zed\ProductOfferGui\Communication\Controller\ViewController::indexAction()
     *
     * @var string
     */
    public const URL_VIEW = '/product-offer-gui/view';
}
