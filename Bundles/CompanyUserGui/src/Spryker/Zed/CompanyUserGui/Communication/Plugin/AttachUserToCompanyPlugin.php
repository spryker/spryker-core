<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class AttachUserToCompanyPlugin extends AbstractPlugin implements CustomerTableActionPluginInterface
{
    protected const URL_ATTACH_CUSTOMER_TO_COMPANY = 'company-user-gui/attach-customer-company?id-customer=%s';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string[] $buttons
     *
     * @return string[]
     */
    public function execute(CustomerTransfer $customerTransfer, array $buttons): array
    {
        $countActiveCompanyUsersByIdCustomer = $this->getFactory()
            ->getCompanyUserFacade()
            ->countActiveCompanyUsersByIdCustomer($customerTransfer);

        if ($countActiveCompanyUsersByIdCustomer !== 0) {
            return $buttons;
        }

        $attachCustomerToCompanyButton = sprintf(
            '<a href="%s" class="btn btn-xs btn-outline  btn-edit"><i class="fa fa-pencil-square-o"></i> Attach to company</a>',
            sprintf(static::URL_ATTACH_CUSTOMER_TO_COMPANY, $customerTransfer->getIdCustomer())
        );
        $buttons[] = $attachCustomerToCompanyButton;

        return $buttons;
    }
}
