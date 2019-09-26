<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
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
    public function saveLabelToAddressRelations(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): void {
        $labelCollection = $companyUnitAddressTransfer->getLabelCollection();
        if (empty($labelCollection) || empty($labelCollection->getLabels())) {
            return;
        }

        foreach ($labelCollection->getLabels() as $label) {
            $labelAddressRelation = $this->getFactory()->createCompanyUnitAddressLabelToCompanyUnitAddressQuery()
                ->filterByFkCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress())
                ->filterByFkCompanyUnitAddressLabel($label->getIdCompanyUnitAddressLabel())
                ->findOneOrCreate();

            $labelAddressRelation->save();
        }
    }

    /**
     * @param array $labelToAddressRelationIds
     *
     * @return void
     */
    public function deleteRedundantLabelToAddressRelations(
        array $labelToAddressRelationIds
    ): void {
        $this->getFactory()
            ->createCompanyUnitAddressLabelToCompanyUnitAddressQuery()
            ->filterByIdCompanyUnitAddressLabelToCompanyUnitAddress_In(
                $labelToAddressRelationIds
            )
            ->delete();
    }
}
