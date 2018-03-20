<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business;

use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelEntityManagerInterface getEntityManager()
 */
class CompanyUnitAddressLabelFacade extends AbstractFacade implements CompanyUnitAddressLabelFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function saveLabelToAddressRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->getFactory()->createCompanyUnitAddressLabelRelationSaver()
            ->saveLabelToAddressRelations($companyUnitAddressTransfer);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function hydrateCompanyUnitAddressWithLabelCollection(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        return $this->getFactory()
            ->createCompanyUnitAddressHydrator()
            ->hydrate($companyUnitAddressTransfer);
    }
}
