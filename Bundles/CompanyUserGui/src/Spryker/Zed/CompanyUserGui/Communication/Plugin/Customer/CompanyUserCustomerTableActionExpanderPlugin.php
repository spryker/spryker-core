<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Plugin\Customer;

use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CompanyUserCustomerTableActionExpanderPlugin extends AbstractPlugin implements CustomerTableActionExpanderPluginInterface
{
    protected const BUTTON_ATTACH_CUSTOMER_TO_COMPANY_URL = 'company-user-gui/create-company-user/attach-customer';
    protected const BUTTON_ATTACH_CUSTOMER_TO_COMPANY_TITLE = 'Attach to company';

    /**
     * {@inheritdoc}
     * - Adds "Attach to company" button in actions for customer table
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

        if ($countActiveCompanyUsersByIdCustomer !== 0) {
            return $buttons;
        }

        $defaultOptions = [
            'class' => 'btn-create',
            'icon' => 'fa-plus',
        ];

        $url = Url::generate(
            static::BUTTON_ATTACH_CUSTOMER_TO_COMPANY_URL,
            [
                CompanyUserGuiConfig::PARAM_ID_CUSTOMER => $idCustomer,
            ]
        );

        $buttons[] = (new ButtonTransfer())
            ->setUrl($url)
            ->setTitle(static::BUTTON_ATTACH_CUSTOMER_TO_COMPANY_TITLE)
            ->setDefaultOptions($defaultOptions);

        return $buttons;
    }
}
