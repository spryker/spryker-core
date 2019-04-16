<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ContentProductGui\Communication\Form\Constraints\ContentProductAbstractListConstraint;
use Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelper;
use Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelperInterface;
use Spryker\Zed\ContentProductGui\Communication\Table\ProductAbstractSelectedTable;
use Spryker\Zed\ContentProductGui\Communication\Table\ProductAbstractViewTable;
use Spryker\Zed\ContentProductGui\ContentProductGuiDependencyProvider;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductInterface;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToLocaleInterface;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

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
     * @return \Spryker\Zed\ContentProductGui\Communication\Form\Constraints\ContentProductAbstractListConstraint
     */
    public function createContentProductAbstractListConstraint(): ContentProductAbstractListConstraint
    {
        return new ContentProductAbstractListConstraint($this->getContentProductFacade());
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
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductQueryContainer(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(ContentProductGuiDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToLocaleInterface
     */
    public function getLocaleFacade(): ContentProductGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ContentProductGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToContentProductInterface
     */
    public function getContentProductFacade(): ContentProductGuiToContentProductInterface
    {
        return $this->getProvidedDependency(ContentProductGuiDependencyProvider::FACADE_CONTENT_PRODUCT);
    }
}
