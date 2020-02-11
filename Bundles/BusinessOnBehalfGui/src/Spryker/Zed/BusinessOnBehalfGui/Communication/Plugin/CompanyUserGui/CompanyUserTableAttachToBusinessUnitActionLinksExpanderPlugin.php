<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Plugin\CompanyUserGui;

use Generated\Shared\Transfer\ButtonTransfer;
use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableActionExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class CompanyUserTableAttachToBusinessUnitActionLinksExpanderPlugin extends AbstractPlugin implements CompanyUserTableActionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds "Attach to business unit" action button to company user table actions.
     *
     * @api
     *
     * @param array $companyUserTableRowItem
     * @param string[] $buttons
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    public function expand(array $companyUserTableRowItem, array $buttons): ButtonTransfer
    {
        return $this->getFactory()
            ->createCompanyUserTableButtonCreator()
            ->addAttachCustomerToBusinessUnitButton($companyUserTableRowItem, $buttons);
    }
}
