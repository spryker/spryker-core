<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductLabelBuilder;
use Generated\Shared\DataBuilder\ProductLabelLocalizedAttributesBuilder;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelStoreQuery;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductLabelDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function haveProductLabel(array $seedData = []): ProductLabelTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer */
        $productLabelTransfer = (new ProductLabelBuilder($seedData + [
                ProductLabelTransfer::VALID_FROM => null,
                ProductLabelTransfer::VALID_TO => null,
            ]))->build();
        $productLabelTransfer->setIdProductLabel(null);

        $productLabelTransfer->setPosition($seedData[ProductLabelTransfer::POSITION] ?? 0);

        $productLabelLocalizedAttributesTransfer = (new ProductLabelLocalizedAttributesBuilder([
            ProductLabelLocalizedAttributesTransfer::FK_LOCALE => $this->getLocator()->locale()->facade()->getCurrentLocale()->getIdLocale(),
        ]))->build();
        $productLabelTransfer->addLocalizedAttributes($productLabelLocalizedAttributesTransfer);

        $this->getProductLabelFacade()->createLabel($productLabelTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productLabelTransfer): void {
            $this->cleanupProductLabelProductAbstractRelations($productLabelTransfer->getIdProductLabel());
            $this->cleanupProductLabelLocalizedAttributes($productLabelTransfer->getIdProductLabel());
            $this->cleanupProductLabelStoreRelations($productLabelTransfer->getIdProductLabel());
            $this->cleanupProductLabel($productLabelTransfer->getIdProductLabel());
        });

        return $this->getProductLabelFacade()->findLabelByLabelName($productLabelTransfer->getName());
    }

    /**
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function haveProductLabelToAbstractProductRelation(int $idProductLabel, int $idProductAbstract): void
    {
        $this
            ->getProductLabelFacade()
            ->addAbstractProductRelationsForLabel($idProductLabel, [$idProductAbstract]);
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    protected function cleanupProductLabelProductAbstractRelations(int $idProductLabel): void
    {
        $this->getProductLabelQuery()
            ->queryProductAbstractRelationsByIdProductLabel($idProductLabel)
            ->find()
            ->delete();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    protected function cleanupProductLabelLocalizedAttributes(int $idProductLabel): void
    {
        $this->getProductLabelQuery()
            ->queryLocalizedAttributesByIdProductLabel($idProductLabel)
            ->find()
            ->delete();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    protected function cleanupProductLabelStoreRelations(int $idProductLabel): void
    {
        SpyProductLabelStoreQuery::create()
            ->filterByFkProductLabel($idProductLabel)
            ->find()
            ->delete();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    protected function cleanupProductLabel(int $idProductLabel): void
    {
        $this->getProductLabelQuery()
            ->queryProductLabelById($idProductLabel)
            ->find()
            ->delete();
    }

        /**
         * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
         */
    protected function getProductLabelFacade(): ProductLabelFacadeInterface
    {
        return $this->getLocator()->productLabel()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected function getProductLabelQuery(): ProductLabelQueryContainerInterface
    {
        return $this->getLocator()->productLabel()->queryContainer();
    }
}
