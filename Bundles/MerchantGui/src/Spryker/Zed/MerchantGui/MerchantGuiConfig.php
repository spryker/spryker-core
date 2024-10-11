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
     *
     * @var string
     */
    public const URL_MERCHANT_LIST = '/merchant-gui/list-merchant';

    /**
     * @uses \Spryker\Zed\MerchantGui\Communication\Controller\EditMerchantController::indexAction()
     *
     * @var string
     */
    public const URL_MERCHANT_EDIT = '/merchant-gui/edit-merchant';

    /**
     * Specification:
     * - Base url for viewing a merchant’s page.
     *
     * @uses \Spryker\Zed\MerchantGui\Communication\Controller\ViewMerchantController::indexAction()
     *
     * @api
     *
     * @var string
     */
    public const URL_MERCHANT_VIEW = '/merchant-gui/view-merchant';

    /**
     * @uses \Spryker\Zed\MerchantGui\Communication\Controller\EditMerchantController::activateAction()
     *
     * @var string
     */
    public const URL_MERCHANT_ACTIVATE = '/merchant-gui/edit-merchant/activate';

    /**
     * @uses \Spryker\Zed\MerchantGui\Communication\Controller\EditMerchantController::deactivateAction()
     *
     * @var string
     */
    public const URL_MERCHANT_DEACTIVATE = '/merchant-gui/edit-merchant/deactivate';

    /**
     * @uses \Spryker\Zed\MerchantGui\Communication\Controller\MerchantStatusController::indexAction()
     *
     * @var string
     */
    public const URL_MERCHANT_STATUS = '/merchant-gui/merchant-status';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_DENIED
     *
     * @var string
     */
    public const STATUS_DENIED = 'denied';

    /**
     * @var string
     */
    protected const PREFIX_MERCHANT_URL = 'merchant';

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantUrlPrefix(): string
    {
        return static::PREFIX_MERCHANT_URL;
    }
}
