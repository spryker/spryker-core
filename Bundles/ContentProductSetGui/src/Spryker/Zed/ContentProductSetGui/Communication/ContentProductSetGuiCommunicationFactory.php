<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication;

use Orm\Zed\ProductSet\Persistence\SpyProductSetQuery;
use Spryker\Zed\ContentProductSetGui\Communication\Mapper\ContentGui\ContentProductSetGuiEditorConfigurationMapper;
use Spryker\Zed\ContentProductSetGui\Communication\Mapper\ContentGui\ContentProductSetGuiEditorConfigurationMapperInterface;
use Spryker\Zed\ContentProductSetGui\Communication\Table\ProductSetSelectedTable;
use Spryker\Zed\ContentProductSetGui\Communication\Table\ProductSetViewTable;
use Spryker\Zed\ContentProductSetGui\ContentProductSetGuiDependencyProvider;
use Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToLocaleInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ContentProductSetGui\ContentProductSetGuiConfig getConfig()
 */
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
            $this->getProductSetQuery(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierPostfix
        );
    }

    /**
     * @param int|null $idProductSet
     * @param string|null $identifierPostfix
     *
     * @return \Spryker\Zed\ContentProductSetGui\Communication\Table\ProductSetSelectedTable
     */
    public function createProductSetSelectedTable(?int $idProductSet, ?string $identifierPostfix = null): ProductSetSelectedTable
    {
        return new ProductSetSelectedTable(
            $this->getProductSetQuery(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierPostfix,
            $idProductSet
        );
    }

    /**
     * @return \Spryker\Zed\ContentProductSetGui\Communication\Mapper\ContentGui\ContentProductSetGuiEditorConfigurationMapperInterface
     */
    public function createContentProductSetGuiEditorMapper(): ContentProductSetGuiEditorConfigurationMapperInterface
    {
        return new ContentProductSetGuiEditorConfigurationMapper($this->getConfig());
    }

    /**
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function getProductSetQuery(): SpyProductSetQuery
    {
        return $this->getProvidedDependency(ContentProductSetGuiDependencyProvider::PROPEL_QUERY_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ContentProductSetGui\Dependency\Facade\ContentProductSetGuiToLocaleInterface
     */
    public function getLocaleFacade(): ContentProductSetGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ContentProductSetGuiDependencyProvider::FACADE_LOCALE);
    }
}
