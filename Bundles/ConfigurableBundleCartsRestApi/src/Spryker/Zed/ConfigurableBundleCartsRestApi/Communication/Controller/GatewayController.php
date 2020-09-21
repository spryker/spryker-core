<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartsRestApi\Communication\Controller;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundleAction(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer {
        return $this->getFacade()->addConfiguredBundle($createConfiguredBundleRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundleToGuestCartAction(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer {
        return $this->getFacade()->addConfiguredBundleToGuestCart($createConfiguredBundleRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantityAction(
        UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer {
        return $this->getFacade()->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundleAction(
        UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer {
        return $this->getFacade()->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }
}
