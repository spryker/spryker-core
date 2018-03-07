<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication;

use Spryker\Zed\CompanyUnitAddressLabel\Communication\Form\CompanyUnitAddressLabelChoiceFormType;
use Spryker\Zed\CompanyUnitAddressLabel\Communication\Form\DataProvider\CompanyUnitAddressLabelFormDataProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\CompanyUnitAddressLabelConfig getConfig()
 */
class CompanyUnitAddressLabelCommunicationFactory extends AbstractCommunicationFactory
{
    //TODO: remove
    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUnitAddressLabelChoiceForm(int $idCompanyUnitAddress)
    {
        $companyUnitAddressDataProvider = $this->createCompanyUnitAddressLabelChoiceFormDataProvider();

        return $this->getFormFactory()->create(
            CompanyUnitAddressLabelChoiceFormType::class,
            $companyUnitAddressDataProvider->getData($idCompanyUnitAddress),
            $companyUnitAddressDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Communication\Form\DataProvider\CompanyUnitAddressLabelFormDataProvider
     */
    public function createCompanyUnitAddressLabelChoiceFormDataProvider()
    {
        return new CompanyUnitAddressLabelFormDataProvider(
            $this->getRepository()
        );
    }
}
