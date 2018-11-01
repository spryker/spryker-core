<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper\PriceVolumeCollectionDataMapper;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper\PriceVolumeCollectionDataMapperInterface;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\FormHandler\PriceVolumeCollectionFormHandler;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\PriceVolumeCollectionFormType;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToStoreFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig getConfig()
 */
class PriceProductVolumeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getPriceVolumeCollectionFormType(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(PriceVolumeCollectionFormType::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider
     */
    public function createPriceVolumeCollectionDataProvider(): PriceVolumeCollectionDataProvider
    {
        return new PriceVolumeCollectionDataProvider(
            $this->getPriceProductFacade(),
            $this->getCurrencyFacade(),
            $this->getStoreFacade(),
            $this->getUtilEncodingService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper\PriceVolumeCollectionDataMapperInterface
     */
    public function createPriceVolumeCollectionDataMapper(): PriceVolumeCollectionDataMapperInterface
    {
        return new PriceVolumeCollectionDataMapper(
            $this->getUtilEncodingService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductVolumeGui\Communication\Form\FormHandler\PriceVolumeCollectionFormHandler
     */
    public function createPriceVolumeCollectionFormHandler(): PriceVolumeCollectionFormHandler
    {
        return new PriceVolumeCollectionFormHandler(
            $this->getPriceProductFacade(),
            $this->createPriceVolumeCollectionDataMapper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): PriceProductVolumeGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductVolumeGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): PriceProductVolumeGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductVolumeGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
