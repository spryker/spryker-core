<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class StoreGuiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Base url for a page with a list of stores.
     *
     * @api
     *
     * @see \Spryker\Zed\StoreGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    public const URL_STORE_LIST = '/store-gui/list';
}
