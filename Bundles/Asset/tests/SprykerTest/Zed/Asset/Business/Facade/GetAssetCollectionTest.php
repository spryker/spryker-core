<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Asset\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AssetConditionsTransfer;
use Generated\Shared\Transfer\AssetCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use SprykerTest\Zed\Asset\AssetBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Asset
 * @group Business
 * @group Facade
 * @group GetAssetCollectionTest
 * Add your own group annotations below this line
 */
class GetAssetCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_ASSET = -1;

    /**
     * @var \SprykerTest\Zed\Asset\AssetBusinessTester
     */
    protected AssetBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetAssetCollectionReturnsEmptyCollectionWhileNoCriteriaMatched(): void
    {
        // Arrange
        $this->tester->haveAsset();

        $assetConditionsTransfer = (new AssetConditionsTransfer())->addIdAsset(static::ID_ASSET);
        $assetCriteriaTransfer = (new AssetCriteriaTransfer())->setAssetConditions($assetConditionsTransfer);

        // Act
        $assetCollectionTransfer = $this->tester->getFacade()->getAssetCollection($assetCriteriaTransfer);

        // Assert
        $this->assertCount(0, $assetCollectionTransfer->getAssets());
    }

    /**
     * @return void
     */
    public function testGetAssetCollectionReturnsCollectionWithOneAssetWhileAssetCriteriaMatched(): void
    {
        // Arrange
        $assetTransfer = $this->tester->haveAsset();
        $this->tester->haveAsset();

        $assetConditionsTransfer = (new AssetConditionsTransfer())->addIdAsset($assetTransfer->getIdAsset());
        $assetCriteriaTransfer = (new AssetCriteriaTransfer())->setAssetConditions($assetConditionsTransfer);

        // Act
        $assetCollectionTransfer = $this->tester->getFacade()->getAssetCollection($assetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $assetCollectionTransfer->getAssets());
        $this->assertSame($assetTransfer->getIdAsset(), $assetCollectionTransfer->getAssets()->getIterator()->current()->getIdAsset());
    }

    /**
     * @return void
     */
    public function testGetAssetCollectionReturnsCollectionWithFiveAssetsWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        for ($i = 0; $i < 15; $i++) {
            $this->tester->haveAsset();
        }

        $assetCriteriaTransfer = (new AssetCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(5)->setOffset(10),
            );

        // Act
        $assetCollectionTransfer = $this->tester->getFacade()->getAssetCollection($assetCriteriaTransfer);

        // Assert
        $this->assertCount(5, $assetCollectionTransfer->getAssets());
    }
}
