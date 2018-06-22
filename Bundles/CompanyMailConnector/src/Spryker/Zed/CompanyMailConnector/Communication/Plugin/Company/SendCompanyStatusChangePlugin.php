<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyMailConnector\Communication\Plugin\Company;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyMailConnector\Communication\CompanyMailConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyMailConnector\Business\CompanyMailConnectorFacadeInterface getFacade()
 */
class SendCompanyStatusChangePlugin extends AbstractPlugin implements CompanyPostSavePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function postSave(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();

        if ($companyTransfer->isPropertyModified(CompanyTransfer::STATUS)) {
            $this->getFacade()->sendCompanyStatusEmail($companyTransfer);
        }

        return $companyResponseTransfer;
    }
}
