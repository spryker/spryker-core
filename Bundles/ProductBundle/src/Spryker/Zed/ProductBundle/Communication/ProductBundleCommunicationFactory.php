<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleReturnCreateFormDataProvider;
use Spryker\Zed\ProductBundle\Communication\Form\Handler\ProductBundleReturnCreateFormHandler;
use Spryker\Zed\ProductBundle\Communication\Form\Handler\ProductBundleReturnCreateFormHandlerInterface;
use Spryker\Zed\ProductBundle\Communication\Form\ReturnCreateBundleForm;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 */
class ProductBundleCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createReturnCreateBundleForm(): FormTypeInterface
    {
        return new ReturnCreateBundleForm();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Communication\Form\DataProvider\ProductBundleReturnCreateFormDataProvider
     */
    public function createProductBundleReturnCreateFormDataProvider(): ProductBundleReturnCreateFormDataProvider
    {
        return new ProductBundleReturnCreateFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Communication\Form\Handler\ProductBundleReturnCreateFormHandlerInterface
     */
    public function createProductBundleReturnCreateFormHandler(): ProductBundleReturnCreateFormHandlerInterface
    {
        return new ProductBundleReturnCreateFormHandler();
    }
}
