<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolver;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolverInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\HardThresholdFormMapper;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdFormMapperInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdMapperResolver;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdMapperResolverInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\ThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinder;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Table\MerchantRelationshipSalesOrderThresholdTable;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToMerchantRelationshipSalesOrderThresholdFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToMoneyFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToStoreFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Persistence\MerchantRelationshipSalesOrderThresholdGuiRepositoryInterface getRepository()
 */
class MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Table\MerchantRelationshipSalesOrderThresholdTable
     */
    public function createMerchantRelationshipSalesOrderThresholdTable(): MerchantRelationshipSalesOrderThresholdTable
    {
        return new MerchantRelationshipSalesOrderThresholdTable(
            $this->getRepository()
        );
    }

    /**
     * @param int $idMerchantRelationship
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createThresholdForm(
        int $idMerchantRelationship,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): FormInterface {
        $formDataProvider = $this->createThresholdFormDataProvider();

        return $this->getFormFactory()->create(
            ThresholdType::class,
            $formDataProvider->getData($idMerchantRelationship, $storeTransfer, $currencyTransfer),
            $formDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdDataProvider
     */
    public function createThresholdFormDataProvider(): ThresholdDataProvider
    {
        return new ThresholdDataProvider(
            $this->getMerchantRelationshipSalesOrderThresholdFacade(),
            $this->getCurrencyFacade(),
            $this->createSoftThresholdDataProviderResolver()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    public function createStoreCurrencyFinder(): StoreCurrencyFinderInterface
    {
        return new StoreCurrencyFinder(
            $this->getCurrencyFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdFormMapperInterface
     */
    public function createHardThresholdFormMapper(): ThresholdFormMapperInterface
    {
        return new HardThresholdFormMapper(
            $this->getLocaleFacade(),
            $this->createStoreCurrencyFinder()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdMapperResolverInterface
     */
    public function createSoftThresholdFormMapperResolver(): ThresholdMapperResolverInterface
    {
        return new ThresholdMapperResolver(
            $this->getLocaleFacade(),
            $this->createStoreCurrencyFinder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolverInterface
     */
    public function createSoftThresholdDataProviderResolver(): ThresholdDataProviderResolverInterface
    {
        return new ThresholdDataProviderResolver(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): MerchantRelationshipSalesOrderThresholdGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantRelationshipSalesOrderThresholdGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): MerchantRelationshipSalesOrderThresholdGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToMerchantRelationshipSalesOrderThresholdFacadeInterface
     */
    public function getMerchantRelationshipSalesOrderThresholdFacade(): MerchantRelationshipSalesOrderThresholdGuiToMerchantRelationshipSalesOrderThresholdFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdGuiDependencyProvider::FACADE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD);
    }
}
