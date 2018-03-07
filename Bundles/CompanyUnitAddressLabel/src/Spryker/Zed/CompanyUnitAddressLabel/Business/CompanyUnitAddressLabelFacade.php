<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelEntityManagerInterface getEntityManager()
 */
class CompanyUnitAddressLabelFacade extends AbstractFacade implements CompanyUnitAddressLabelFacadeInterface
{

    //TODO: rename to save
    //Inherit doc
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function updateLabelToAddressRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer)
    {
        $this->getEntityManager()
            ->saveLabelToAddressRelation($companyUnitAddressTransfer);
    }
}
