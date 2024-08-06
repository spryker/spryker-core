<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Communication\Plugin\KernelApp;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * {@inheritDoc}
 *
 * @api
 *
 * @method \Spryker\Zed\MerchantApp\MerchantAppConfig getConfig()
 * @method \Spryker\Zed\MerchantApp\Business\MerchantAppFacadeInterface getFacade()
 */
class MerchantAppRequestExpanderPlugin extends AbstractPlugin implements RequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    public function expandRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
    {
        return $this->getFacade()->addMerchantReferenceHeader($acpHttpRequestTransfer);
    }
}
