<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValueQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdDataProvider;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolver;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolverInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\HardThresholdFormMapper;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdFormMapperInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdMapperResolver;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdMapperResolverInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinder;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Table\MerchantRelationshipMinimumOrderValueTable;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMerchantRelationshipMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMoneyFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToStoreFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig getConfig()
 */
class MerchantRelationshipMinimumOrderValueGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Table\MerchantRelationshipMinimumOrderValueTable
     */
    public function createMerchantRelationshipMinimumOrderValueTable(): MerchantRelationshipMinimumOrderValueTable
    {
        return new MerchantRelationshipMinimumOrderValueTable(
            $this->getMerchantRelationshipPropelQuery(),
            $this->getMerchantRelationshipMinimumOrderValuePropelQuery()
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
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdDataProvider
     */
    public function createThresholdFormDataProvider(): ThresholdDataProvider
    {
        return new ThresholdDataProvider(
            $this->getMerchantRelationshipMinimumOrderValueFacade(),
            $this->getCurrencyFacade(),
            $this->createSoftThresholdDataProviderResolver()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider
     */
    public function createLocaleProvider(): LocaleProvider
    {
        return new LocaleProvider(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    public function createStoreCurrencyFinder(): StoreCurrencyFinderInterface
    {
        return new StoreCurrencyFinder(
            $this->getCurrencyFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdFormMapperInterface
     */
    public function createHardThresholdFormMapper(): ThresholdFormMapperInterface
    {
        return new HardThresholdFormMapper(
            $this->createLocaleProvider(),
            $this->createStoreCurrencyFinder()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper\ThresholdMapperResolverInterface
     */
    public function createSoftThresholdFormMapperResolver(): ThresholdMapperResolverInterface
    {
        return new ThresholdMapperResolver(
            $this->createLocaleProvider(),
            $this->createStoreCurrencyFinder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolverInterface
     */
    public function createSoftThresholdDataProviderResolver(): ThresholdDataProviderResolverInterface
    {
        return new ThresholdDataProviderResolver(
            $this->getConfig()
        );
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    public function getMerchantRelationshipPropelQuery(): SpyMerchantRelationshipQuery
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueGuiDependencyProvider::PROPEL_QUERY_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValueQuery
     */
    public function getMerchantRelationshipMinimumOrderValuePropelQuery(): SpyMerchantRelationshipMinimumOrderValueQuery
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueGuiDependencyProvider::PROPEL_QUERY_MERCHANT_RELATIONSHIP_MINIMUM_ORDER_VALUE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantRelationshipMinimumOrderValueGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): MerchantRelationshipMinimumOrderValueGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMerchantRelationshipMinimumOrderValueFacadeInterface
     */
    public function getMerchantRelationshipMinimumOrderValueFacade(): MerchantRelationshipMinimumOrderValueGuiToMerchantRelationshipMinimumOrderValueFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueGuiDependencyProvider::FACADE_MERCHANT_RELATIONSHIP_MINIMUM_ORDER_VALUE);
    }
}
