<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormEventListenerExpander;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormEventListenerExpanderInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormExpander;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormExpanderInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormViewExpander;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormViewExpanderInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProvider;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProviderInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Transformer\ServiceDataTransformer;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Validator\ServiceValidator;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Validator\ServiceValidatorInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Facade\ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Service\ProductOfferServicePointMerchantPortalGuiToUtilEncodingInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\ProductOfferServicePointMerchantPortalGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Twig\Environment;

/**
 * @method \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\ProductOfferServicePointMerchantPortalGuiConfig getConfig()
 */
class ProductOfferServicePointMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormExpanderInterface
     */
    public function createServiceProductOfferFormExpander(): ServiceProductOfferFormExpanderInterface
    {
        return new ServiceProductOfferFormExpander(
            $this->getUtilEncodingService(),
            $this->createServiceDataTransformer(),
            $this->createServiceDataProvider(),
            $this->createServiceProductOfferFormEventListenerExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormEventListenerExpanderInterface
     */
    public function createServiceProductOfferFormEventListenerExpander(): ServiceProductOfferFormEventListenerExpanderInterface
    {
        return new ServiceProductOfferFormEventListenerExpander(
            $this->createServiceDataTransformer(),
            $this->createServiceDataProvider(),
            $this->createServiceValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormViewExpanderInterface
     */
    public function createServiceProductOfferFormViewExpander(): ServiceProductOfferFormViewExpanderInterface
    {
        return new ServiceProductOfferFormViewExpander($this->getTwigEnvironment());
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProviderInterface
     */
    public function createServiceDataProvider(): ServiceDataProviderInterface
    {
        return new ServiceDataProvider($this->getServicePointFacade());
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createServiceDataTransformer(): DataTransformerInterface
    {
        return new ServiceDataTransformer();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Validator\ServiceValidatorInterface
     */
    public function createServiceValidator(): ServiceValidatorInterface
    {
        return new ServiceValidator();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Facade\ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointMerchantPortalGuiDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Service\ProductOfferServicePointMerchantPortalGuiToUtilEncodingInterface
     */
    public function getUtilEncodingService(): ProductOfferServicePointMerchantPortalGuiToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(ProductOfferServicePointMerchantPortalGuiDependencyProvider::SERVICE_TWIG);
    }
}
