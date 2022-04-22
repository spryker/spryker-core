<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Expander\PriceProductAbstractTableConfigurationExpander;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Expander\PriceProductAbstractTableConfigurationExpanderInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Expander\PriceProductConcreteTableConfigurationExpander;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Expander\PriceProductConcreteTableConfigurationExpanderInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Filter\MerchantRelationshipPriceProductFilter;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Filter\MerchantRelationshipPriceProductFilterInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Mapper\MerchantRelationshipPriceProductMapper;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Mapper\MerchantRelationshipPriceProductMapperInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReader;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Validator\MerchantRelationshipVolumePriceProductValidator;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Validator\MerchantRelationshipVolumePriceProductValidatorInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Service\PriceProductMerchantRelationshipMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\PriceProductMerchantRelationshipMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\PriceProductMerchantRelationshipMerchantPortalGuiConfig getConfig()
 */
class PriceProductMerchantRelationshipMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Expander\PriceProductAbstractTableConfigurationExpanderInterface
     */
    public function createPriceProductAbstractTableConfigurationExpander(): PriceProductAbstractTableConfigurationExpanderInterface
    {
        return new PriceProductAbstractTableConfigurationExpander(
            $this->getMerchantUserFacade(),
            $this->getMerchantRelationshipFacade(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Expander\PriceProductConcreteTableConfigurationExpanderInterface
     */
    public function createPriceProductConcreteTableConfigurationExpander(): PriceProductConcreteTableConfigurationExpanderInterface
    {
        return new PriceProductConcreteTableConfigurationExpander(
            $this->getMerchantUserFacade(),
            $this->getMerchantRelationshipFacade(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Mapper\MerchantRelationshipPriceProductMapperInterface
     */
    public function createMerchantRelationshipPriceProductMapper(): MerchantRelationshipPriceProductMapperInterface
    {
        return new MerchantRelationshipPriceProductMapper(
            $this->createMerchantRelationshipReader(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface
     */
    public function createMerchantRelationshipReader(): MerchantRelationshipReaderInterface
    {
        return new MerchantRelationshipReader(
            $this->getMerchantRelationshipFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Filter\MerchantRelationshipPriceProductFilterInterface
     */
    public function createMerchantRelationshipPriceProductFilter(): MerchantRelationshipPriceProductFilterInterface
    {
        return new MerchantRelationshipPriceProductFilter();
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Validator\MerchantRelationshipVolumePriceProductValidatorInterface
     */
    public function createMerchantRelationshipVolumePriceProductValidator(): MerchantRelationshipVolumePriceProductValidatorInterface
    {
        return new MerchantRelationshipVolumePriceProductValidator(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): PriceProductMerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): PriceProductMerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Service\PriceProductMerchantRelationshipMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductMerchantRelationshipMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
