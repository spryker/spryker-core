<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyMailConnector\Business;

use Generated\Shared\Transfer\CompanyTransfer;

class CompanyMailConnectorFacade implements CompanyMailConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function sendCompanyStatusEmail(CompanyTransfer $companyTransfer): void
    {
        // TODO: Implement sendCompanyStatusEmail() method.
    }
}
