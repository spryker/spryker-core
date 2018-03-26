<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyMailConnector\Business;

use Generated\Shared\Transfer\CompanyTransfer;

interface CompanyMailConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Send email to the first company user if company status was changed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function sendCompanyStatusEmail(CompanyTransfer $companyTransfer): void;
}
