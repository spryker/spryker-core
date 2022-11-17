<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductCategoryBuilder;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductCategoryDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param int $idCategory
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer
     */
    public function haveProductCategoryForCategory(int $idCategory, array $seedData): ProductCategoryTransfer
    {
        $productCategoryTransfer = $this->generateProductCategoryTransfer($seedData);

        $spyProductCategoryEntity = new SpyProductCategory();

        $spyProductCategoryEntity->fromArray($productCategoryTransfer->toArray());
        $spyProductCategoryEntity->setFkCategory($idCategory);
        $spyProductCategoryEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($spyProductCategoryEntity): void {
            $this->cleanupProductCategory($spyProductCategoryEntity);
        });

        return $productCategoryTransfer->fromArray(
            $spyProductCategoryEntity->toArray(),
            true,
        );
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer
     */
    protected function generateProductCategoryTransfer(array $seedData = []): ProductCategoryTransfer
    {
        return (new ProductCategoryBuilder($seedData))->build();
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategoryEntity
     *
     * @return void
     */
    protected function cleanupProductCategory(SpyProductCategory $productCategoryEntity): void
    {
        SpyProductCategoryQuery::create()
            ->filterByIdProductCategory($productCategoryEntity->getIdProductCategory())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param array<string> $localeNames
     *
     * @return void
     */
    public function assertCategoryContainsNecessaryLocales(CategoryTransfer $categoryTransfer, array $localeNames): void
    {
        $localizedAttributeTransferCollection = $categoryTransfer->getLocalizedAttributes();

        $this->assertCount(count($localeNames), $localizedAttributeTransferCollection);

        $fetchedLocaleNames = [];
        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $localizedAttribute */
        foreach ($localizedAttributeTransferCollection as $localizedAttribute) {
            $fetchedLocaleNames[] = $localizedAttribute->getLocale()->getLocaleName();
        }

        $this->assertEqualsCanonicalizing($localeNames, $fetchedLocaleNames);
    }
}
