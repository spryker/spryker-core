<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyMailConnector\Business;

use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyMailConnector\Business\CompanyMailConnectorBusinessFactory getFactory()
 */
class CompanyMailConnectorFacade extends AbstractFacade implements CompanyMailConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function sendCompanyStatusEmail(CompanyTransfer $companyTransfer): void
    {
        $this->getFactory()->createCompanyStatusMailer()->sendCompanyStatusEmail($companyTransfer);
    }
}
