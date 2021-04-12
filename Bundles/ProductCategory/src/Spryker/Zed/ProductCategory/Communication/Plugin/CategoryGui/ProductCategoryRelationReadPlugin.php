<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Plugin\CategoryGui;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryRelationReadPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategory\ProductCategoryConfig getConfig()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface getQueryContainer()
 */
class ProductCategoryRelationReadPlugin extends AbstractPlugin implements CategoryRelationReadPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationName(): string
    {
        return 'Products';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getRelations(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): array
    {
        return $this->getFacade()
            ->getLocalizedProductAbstractNamesByCategory($categoryTransfer, $localeTransfer);
    }
}
