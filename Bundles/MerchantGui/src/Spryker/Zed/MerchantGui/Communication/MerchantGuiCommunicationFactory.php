<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication;

use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantAddressFormDataProvider;
use Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantFormDataProvider;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantAddressForm;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantForm;
use Spryker\Zed\MerchantGui\Communication\Form\Transformer\ArrayObjectToObjectTransformer;
use Spryker\Zed\MerchantGui\Communication\Table\MerchantTable;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantGui\MerchantGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;

class MerchantGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Table\MerchantTable
     */
    public function createMerchantTable(): MerchantTable
    {
        return new MerchantTable($this->getPropelMerchantQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantForm(?MerchantTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantFormDataProvider
     */
    public function createMerchantFormDataProvider(): MerchantFormDataProvider
    {
        return new MerchantFormDataProvider(
            $this->getMerchantFacade()
        );
    }

    /**
     * @param int|null $idMerchantAddress
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantAddressForm(?int $idMerchantAddress = null): FormInterface
    {
        $companyUnitAddressDataProvider = $this->createMerchantAddressFormDataProvider();

        return $this->getFormFactory()->create(
            MerchantAddressForm::class,
            $companyUnitAddressDataProvider->getData($idMerchantAddress),
            $companyUnitAddressDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantAddressFormDataProvider
     */
    public function createMerchantAddressFormDataProvider(): MerchantAddressFormDataProvider
    {
        return new MerchantAddressFormDataProvider(
            $this->getMerchantFacade(),
            $this->getCountryFacade()
        );
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createArrayObjectToObjectTransformer(): DataTransformerInterface
    {
        return new ArrayObjectToObjectTransformer();
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface
     */
    public function getCountryFacade(): MerchantGuiToCountryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    public function getPropelMerchantQuery(): SpyMerchantQuery
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PROPEL_MERCHANT_QUERY);
    }
}
