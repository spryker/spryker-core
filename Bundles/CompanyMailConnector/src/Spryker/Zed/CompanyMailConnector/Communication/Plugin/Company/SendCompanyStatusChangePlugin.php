<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyMailConnector\Communication\Plugin\Company;

use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostSavePluginInterface;

class SendCompanyStatusChangePlugin implements CompanyPostSavePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function postSave(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        return $companyTransfer;
    }
}
