<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Plugin\CompanyUserGui;

use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableActionLinksExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class CompanyUserTableDeleteActionLinksExpanderPlugin extends AbstractPlugin implements CompanyUserTableActionLinksExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds new "Delete" button in actions for company user table for delete without anonymizing of customer
     * - Removes old "Delete" button
     *
     * @api
     *
     * @param array $companyUserDataItem
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $buttonTransfers
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function expandActionLinks(array $companyUserDataItem, array $buttonTransfers): array
    {
        return $this->getFactory()
            ->createButtonCreator()
            ->addNewDeleteCompanyUserButton($companyUserDataItem, $buttonTransfers);
    }
}
