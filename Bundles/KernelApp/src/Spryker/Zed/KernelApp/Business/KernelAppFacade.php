<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Business;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\KernelApp\Business\KernelAppBusinessFactory getFactory()
 * @method \Spryker\Zed\KernelApp\Persistence\KernelAppEntityManagerInterface getEntityManager()
 */
class KernelAppFacade extends AbstractFacade implements KernelAppFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function makeRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer
    {
        return $this->getFactory()->createRequest()->request($acpHttpRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return void
     */
    public function writeAppConfig(AppConfigTransfer $appConfigTransfer): void
    {
        $this->getEntityManager()->writeAppConfig($appConfigTransfer);
    }
}
