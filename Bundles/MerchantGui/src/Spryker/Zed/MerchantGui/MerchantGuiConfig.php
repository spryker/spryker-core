<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\MerchantGui\Communication\Controller\ListMerchantController::indexAction()
     */
    public const URL_MERCHANT_LIST = '/merchant-gui/list-merchant';

    /**
     * @uses \Spryker\Zed\MerchantGui\Communication\Controller\EditMerchantController::indexAction()
     */
    public const URL_MERCHANT_EDIT = '/merchant-gui/edit-merchant';

    /**
     * @uses \Spryker\Zed\MerchantGui\Communication\Controller\MerchantStatusController::indexAction()
     */
    public const URL_MERCHANT_STATUS = '/merchant-gui/merchant-status';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_WAITING_FOR_APPROVAL
     */
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_DENIED
     */
    public const STATUS_DENIED = 'denied';
}
