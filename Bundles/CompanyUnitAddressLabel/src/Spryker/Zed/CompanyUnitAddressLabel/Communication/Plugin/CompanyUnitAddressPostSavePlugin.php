<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication\Plugin;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressPostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Communication\CompanyUnitAddressLabelCommunicationFactory getFactory()
 */
class CompanyUnitAddressPostSavePlugin extends AbstractPlugin implements CompanyUnitAddressPostSavePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function postSave(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        $response = $this->getFacade()->saveLabelToAddressRelations($companyUnitAddressTransfer);

        if ($response->getIsSuccessful()) {
            return $response->getCompanyUnitAddressTransfer();
        }

        return $companyUnitAddressTransfer;
    }
}
