<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelFormDataProvider;
use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelFormType;
use Spryker\Zed\ProductLabelGui\Communication\Table\ProductLabelTable;
use Spryker\Zed\ProductLabelGui\ProductLabelGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig getConfig()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainer getQueryContainer()
 */
class ProductLabelGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Table\ProductLabelTable
     */
    public function createProductLabelTable()
    {
        return new ProductLabelTable($this->getQueryContainer());
    }

    /**
     * @param ProductLabelTransfer $productLabelTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductLabelForm(ProductLabelTransfer $productLabelTransfer = null, array $options = [])
    {
        return $this
            ->getFormFactory()
            ->create(
                $this->createProductLabelFormType(),
                $productLabelTransfer,
                $options
            );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    protected function createProductLabelFormType()
    {
        return new ProductLabelFormType($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelFormDataProvider
     */
    public function createProductLabelFormDataProvider()
    {
        return new ProductLabelFormDataProvider(
            $this->getLocaleFacade(),
            $this->getProductLabelFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    public function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    protected function getProductLabelQueryContainer()
    {

    }

}
