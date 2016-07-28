<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOption\Communication\Form\DataProvider\GeneralFormDataProvider;
use Spryker\Zed\ProductOption\Communication\Form\GeneralForm;
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionForm;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createGeneralForm($data, $options)
    {
        $productOptionForm = $this->createProductOptionForm();

        $generalFormType = new GeneralForm($productOptionForm);

        return $this->getFormFactory()->create(
            $generalFormType,
            null,
            array_merge(['data_class'  => ProductOptionGroupTransfer::class], $options)
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Communication\Form\ProductOptionForm
     */
    public function createProductOptionForm()
    {
        return new ProductOptionForm($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Communication\Form\DataProvider\GeneralFormDataProvider
     */
    public function createGeneralFormDataProvider()
    {
        return new GeneralFormDataProvider(
            $this->getTaxFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TAX);
    }
}
