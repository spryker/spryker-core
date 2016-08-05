<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Product\Communication\Table\ProductTable;
use Spryker\Zed\Product\ProductDependencyProvider;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 */
class ProductCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Product\Communication\Table\ProductTable
     */
    public function createProductTable()
    {
        $productQuery = $this->getQueryContainer()->queryProductAbstract();
        $localeTransfer = $this->createLocaleTransfer();

        return new ProductTable(
            $productQuery,
            $this->getUrlFacade(),
            $localeTransfer,
            $this->getConfig()->getHostYves()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    public function getUrlFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransfer()
    {
        $locale = $this->getLocaleFacade()->getCurrentLocale();

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($locale->toArray());

        return $localeTransfer;
    }

}
