<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Plugin\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig getConfig()
 */
class BusinessOnBehalfGuiAttachToCompanyButtonCustomerTableActionExpanderPlugin extends AbstractPlugin implements CustomerTableActionExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds "Attach to company" button in actions for customer table if customer have a company user
     *
     * @api
     *
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $buttons
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function execute(int $idCustomer, array $buttons): array
    {
        $countActiveCompanyUsersByIdCustomer = $this->getFactory()
            ->getCompanyUserFacade()
            ->countActiveCompanyUsersByIdCustomer((new CustomerTransfer())->setIdCustomer($idCustomer));

        if ($countActiveCompanyUsersByIdCustomer === 0) {
            return $buttons;
        }

        return $this->getFactory()
            ->createGuiButtonCreator()
            ->addAttachToCompanyButtonForCustomerTable($idCustomer, $buttons);
    }
}
