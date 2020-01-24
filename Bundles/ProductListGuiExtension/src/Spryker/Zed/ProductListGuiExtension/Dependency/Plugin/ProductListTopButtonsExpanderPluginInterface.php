<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ButtonCollectionTransfer;

interface ProductListTopButtonsExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands top section of Product List index page with additional buttons.
     *
     * @api
     *
     * @see \Spryker\Zed\ProductListGui\Communication\Controller\IndexController::indexAction()
     *
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function expand(ButtonCollectionTransfer $buttonCollectionTransfer): ButtonCollectionTransfer;
}
