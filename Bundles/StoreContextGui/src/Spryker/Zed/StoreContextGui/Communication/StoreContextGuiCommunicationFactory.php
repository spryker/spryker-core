<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StoreContextGui\Communication\Expander\StoreContextFormExpander;
use Spryker\Zed\StoreContextGui\Communication\Expander\StoreContextFormExpanderInterface;
use Spryker\Zed\StoreContextGui\Communication\Expander\StoreContextTabExpander;
use Spryker\Zed\StoreContextGui\Communication\Expander\StoreContextTabExpanderInterface;
use Spryker\Zed\StoreContextGui\Communication\Form\DataProvider\StoreContextFormDataProvider;
use Spryker\Zed\StoreContextGui\Communication\Form\DataTransformer\StoreContextCollectionDataTransformer;
use Spryker\Zed\StoreContextGui\Communication\Form\StoreContextForm;
use Spryker\Zed\StoreContextGui\Dependency\Facade\StoreContextGuiToStoreContextFacadeInterface;
use Spryker\Zed\StoreContextGui\StoreContextGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @method \Spryker\Zed\StoreContextGui\StoreContextGuiConfig getConfig()
 */
class StoreContextGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StoreContextGui\Communication\Expander\StoreContextTabExpanderInterface
     */
    public function createStoreContextTabExpander(): StoreContextTabExpanderInterface
    {
        return new StoreContextTabExpander();
    }

    /**
     * @return \Spryker\Zed\StoreContextGui\Communication\Form\DataProvider\StoreContextFormDataProvider
     */
    public function createStoreContextFormDataProvider(): StoreContextFormDataProvider
    {
        return new StoreContextFormDataProvider(
            $this->getStoreContextFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreContextGui\Communication\Expander\StoreContextFormExpanderInterface
     */
    public function createStoreContextFormExpander(): StoreContextFormExpanderInterface
    {
        return new StoreContextFormExpander(
            $this->createStoreContextFormDataProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreContextGui\Dependency\Facade\StoreContextGuiToStoreContextFacadeInterface
     */
    public function getStoreContextFacade(): StoreContextGuiToStoreContextFacadeInterface
    {
        return $this->getProvidedDependency(StoreContextGuiDependencyProvider::FACADE_STORE_CONTEXT);
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createStoreContextCollectionDataTransformer(): DataTransformerInterface
    {
        return new StoreContextCollectionDataTransformer();
    }

    /**
     * @return string
     */
    public function getStoreContextFormClass(): string
    {
        return StoreContextForm::class;
    }
}
