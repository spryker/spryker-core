<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductDiscontinuedGui\Communication\Form\DataProvider\DiscontinueProductFormDataProvider;
use Spryker\Zed\ProductDiscontinuedGui\Communication\Form\DiscontinueProductForm;
use Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedGui\ProductDiscontinuedGuiDependencyProvider;
use Symfony\Component\Form\FormTypeInterface;

class ProductDiscontinuedGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createDiscontinueProductForm(): FormTypeInterface
    {
        return new DiscontinueProductForm();
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedGui\Communication\Form\DataProvider\DiscontinueProductFormDataProvider
     */
    public function createDiscontinueProductFormDataProvider(): DiscontinueProductFormDataProvider
    {
        return new DiscontinueProductFormDataProvider(
            $this->getProductDiscontinuedFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade(): ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedGuiDependencyProvider::FACADE_PRODUCT_DISCONTINUED);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductDiscontinuedGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedGuiDependencyProvider::FACADE_LOCALE);
    }
}
