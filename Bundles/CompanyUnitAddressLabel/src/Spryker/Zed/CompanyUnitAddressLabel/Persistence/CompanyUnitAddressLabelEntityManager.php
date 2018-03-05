<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelToCompanyUnitAddressEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelPersistenceFactory getFactory()
 */
class CompanyUnitAddressLabelEntityManager extends AbstractEntityManager implements CompanyUnitAddressLabelEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function saveLabelToAddressRelation(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ) {
        $labelCollection = $companyUnitAddressTransfer->getLabelCollection();
        if (empty($labelCollection) || empty($labelCollection->getLabels())) {
            return;
        }

        $this->deleteLabelRelationsByCompanyUnitAddress($companyUnitAddressTransfer);

        foreach ($labelCollection->getLabels() as $label) {
            $transfer = new SpyCompanyUnitAddressLabelToCompanyUnitAddressEntityTransfer();
            $transfer->setFkCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());
            $transfer->setFkCompanyUnitAddressLabel($label->getIdCompanyUnitAddressLabel());
            $this->save($transfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function deleteLabelRelationsByCompanyUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer)
    {
        $this->getFactory()
            ->createCompanyUnitAddressLabelToCompanyUnitAddressQuery()
            ->filterByFkCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress())
            ->deleteAll();
    }
}
