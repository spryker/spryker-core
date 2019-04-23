<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ContentProductSetGui\Communication\Form\Constraints\ContentProductAbstractListConstraint;
use Spryker\Zed\ContentProductSetGui\Communication\Table\Helper\ProductAbstractTableHelper;
use Spryker\Zed\ContentProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface;
use Spryker\Zed\ContentProductSetGui\Communication\Table\ProductAbstractSelectedTable;
use Spryker\Zed\ContentProductSetGui\Communication\Table\ProductAbstractViewTable;
use Spryker\Zed\ContentProductSetGui\ContentProductSetGuiDependencyProvider;
use Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToContentProductInterface;
use Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToLocaleInterface;
use Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToProductImageInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class ContentProductSetGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param string|null $identifierPostfix
     *
     * @return \Spryker\Zed\ContentProductSetGui\Communication\Table\ProductSetViewTable
     */
    public function createProductSetViewTable(?string $identifierPostfix = null): ProductSetViewTable
    {
        return new ProductSetViewTable(
            $this->getProductSetQueryContainer(),
            $this->createProductSetTableHelper(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierPostfix
        );
    }

    /**
     * @param int $idProductSet
     * @param string|null $identifierPostfix
     *
     * @return \Spryker\Zed\ContentProductSetGui\Communication\Table\ProductSetSelectedTable
     */
    public function createProductSetSelectedTable(int $idProductSet, ?string $identifierPostfix = null): ProductSetSelectedTable
    {
        return new ProductAbstractSelectedTable(
            $this->getProductSetQueryContainer(),
            $this->createProductAbstractTableHelper(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierPostfix,
            $idProductSet
        );
    }

    /**
     * @return \Spryker\Zed\ContentProductSetGui\Communication\Table\Helper\ProductSetTableHelperInterface
     */
    public function createProductSetTableHelper(): ProductSetTableHelperInterface
    {
        return new ProductSetTableHelper();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductSetQueryContainer(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(ContentProductSetGuiDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToLocaleInterface
     */
    public function getLocaleFacade(): ContentProductSetGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ContentProductSetGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToContentProductInterface
     */
    public function getContentProductFacade(): ContentProductSetGuiToContentProductInterface
    {
        return $this->getProvidedDependency(ContentProductSetGuiDependencyProvider::FACADE_CONTENT_PRODUCT);
    }
}
