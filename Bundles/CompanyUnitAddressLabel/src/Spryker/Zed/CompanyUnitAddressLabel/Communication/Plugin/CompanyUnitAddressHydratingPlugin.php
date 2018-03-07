<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication\Plugin;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressHydratingPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Communication\CompanyUnitAddressLabelCommunicationFactory getFactory()
 */
class CompanyUnitAddressHydratingPlugin extends AbstractPlugin implements CompanyUnitAddressHydratingPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $addressTransfer
     *
     * @return void
     */
    public function hydrate(CompanyUnitAddressTransfer $addressTransfer)
    {
        $this->getFacade()
            ->hydrateCompanyUnitAddressWithLabelCollection($addressTransfer);
    }
}
