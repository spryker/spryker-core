<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ContentProductSetGui\Communication\Table\ProductSetSelectedTable;
use Spryker\Zed\ContentProductSetGui\Communication\Table\ProductSetViewTable;
use Spryker\Zed\ContentProductSetGui\ContentProductSetGuiDependencyProvider;
use Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToLocaleInterface;
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
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierPostfix,
            $idProductSet
        );
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductSetQueryContainer(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(ContentProductSetGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToLocaleInterface
     */
    public function getLocaleFacade(): ContentProductSetGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ContentProductSetGuiDependencyProvider::FACADE_LOCALE);
    }
}
