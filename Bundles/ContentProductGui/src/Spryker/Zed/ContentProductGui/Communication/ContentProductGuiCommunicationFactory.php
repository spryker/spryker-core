<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelper;
use Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelperInterface;
use Spryker\Zed\ContentProductGui\Communication\Table\ProductAbstractSelectedTable;
use Spryker\Zed\ContentProductGui\Communication\Table\ProductAbstractViewTable;
use Spryker\Zed\ContentProductGui\ContentProductGuiDependencyProvider;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToLocaleInterface;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface;
use Spryker\Zed\ContentProductGui\Dependency\QueryContainer\ContentProductGuiToProductInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ContentProductGui\ContentStorageConfig getConfig()
 * @method \Spryker\Zed\ContentProductGui\Persistence\ContentStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ContentProductGui\Persistence\ContentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ContentProductGui\Business\ContentStorageFacadeInterface getFacade()
 */
class ContentProductGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $identifierPostfix
     *
     * @return \Spryker\Zed\ContentProductGui\Communication\Table\ProductAbstractViewTable
     */
    public function createProductAbstractViewTable(
        LocaleTransfer $localeTransfer,
        ?string $identifierPostfix = null
    ): ProductAbstractViewTable {
        return new ProductAbstractViewTable(
            $this->getProductQueryContainer(),
            $this->createProductAbstractTableHelper(),
            $localeTransfer,
            $identifierPostfix
        );
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param array $idProductAbstracts
     * @param string|null $identifierPostfix
     *
     * @return \Spryker\Zed\ContentProductGui\Communication\Table\ProductAbstractSelectedTable
     */
    public function createProductAbstractSelectedTable(
        LocaleTransfer $localeTransfer,
        array $idProductAbstracts,
        ?string $identifierPostfix = null
    ): ProductAbstractSelectedTable {
        return new ProductAbstractSelectedTable(
            $this->getProductQueryContainer(),
            $this->createProductAbstractTableHelper(),
            $localeTransfer,
            $identifierPostfix,
            $idProductAbstracts
        );
    }

    /**
     * @return \Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelperInterface
     */
    public function createProductAbstractTableHelper(): ProductAbstractTableHelperInterface
    {
        return new ProductAbstractTableHelper($this->getProductImageFacade());
    }

    /**
     * @return \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface
     */
    public function getProductImageFacade(): ContentProductGuiToProductImageInterface
    {
        return $this->getProvidedDependency(ContentProductGuiDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ContentProductGui\Dependency\QueryContainer\ContentProductGuiToProductInterface
     */
    public function getProductQueryContainer(): ContentProductGuiToProductInterface
    {
        return $this->getProvidedDependency(ContentProductGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToLocaleInterface
     */
    public function getLocaleFacade(): ContentProductGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ContentProductGuiDependencyProvider::FACADE_LOCALE);
    }
}
