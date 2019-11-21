<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartNote\Communication\Controller;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\ConfigurableBundleCartNote\Business\ConfigurableBundleCartNoteFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfiguredBundleAction(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        return $this->getFacade()->setCartNoteToConfiguredBundle($configuredBundleCartNoteRequestTransfer);
    }
}
