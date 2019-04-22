<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Constraint\ThresholdStrategyConstraint;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\MerchantRelationshipThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\MerchantRelationshipThresholdDataProviderResolver;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\MerchantRelationshipThresholdDataProviderResolverInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver\MerchantRelationshipThresholdFormMapperResolver;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver\MerchantRelationshipThresholdFormMapperResolverInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\MerchantRelationshipThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinder;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Table\MerchantRelationshipSalesOrderThresholdTable;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToCompanyFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToLocaleFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToMerchantRelationshipFacadeInterface;
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
            MerchantRelationshipThresholdType::class,
            $formDataProvider->getData($idMerchantRelationship, $storeTransfer, $currencyTransfer),
            $formDataProvider->getOptions($currencyTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\MerchantRelationshipThresholdDataProvider
     */
    public function createThresholdFormDataProvider(): MerchantRelationshipThresholdDataProvider
    {
        return new MerchantRelationshipThresholdDataProvider(
            $this->getMerchantRelationshipSalesOrderThresholdFacade(),
            $this->getCurrencyFacade(),
            $this->createMerchantRelationshipThresholdDataProviderResolver(),
            $this->getSalesOrderThresholdFormExpanderPlugins()
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
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver\MerchantRelationshipThresholdFormMapperResolverInterface
     */
    public function createMerchantRelationshipThresholdFormMapperResolver(): MerchantRelationshipThresholdFormMapperResolverInterface
    {
        return new MerchantRelationshipThresholdFormMapperResolver(
            $this->getLocaleFacade(),
            $this->getConfig(),
            $this->getSalesOrderThresholdFormExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\MerchantRelationshipThresholdDataProviderResolverInterface
     */
    public function createMerchantRelationshipThresholdDataProviderResolver(): MerchantRelationshipThresholdDataProviderResolverInterface
    {
        return new MerchantRelationshipThresholdDataProviderResolver(
            $this->getConfig(),
            $this->getSalesOrderThresholdFormExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Constraint\ThresholdStrategyConstraint
     */
    public function createThresholdStrategyConstraint(): ThresholdStrategyConstraint
    {
        return new ThresholdStrategyConstraint([
            ThresholdStrategyConstraint::OPTION_SALES_ORDER_THRESHOLD_FORM_EXPANDER_PLUGINS => $this->getSalesOrderThresholdFormExpanderPlugins(),
        ]);
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

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationshipSalesOrderThresholdGuiToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdGuiDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Dependency\Facade\MerchantRelationshipSalesOrderThresholdGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): MerchantRelationshipSalesOrderThresholdGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdGuiDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    public function getSalesOrderThresholdFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdGuiDependencyProvider::SALES_ORDER_THRESHOLD_FORM_EXPANDER_PLUGINS);
    }
}
