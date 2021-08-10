<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclEntityDummyProduct\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\Store\Persistence\SpyStore;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntityDummyProduct
 * @group Business
 * @group Facade
 * @group AclEntityDummyProductFacadeTest
 * Add your own group annotations below this line
 */
class AclEntityDummyProductFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AclEntityDummyProduct\AclEntityDummyProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandAclEntityMetadataCollectionWithProductStoreRelation(): void
    {
        // Arrange
        /** @var \Spryker\Zed\AclEntityDummyProduct\Business\AclEntityDummyProductFacadeInterface $aclEntityDummyProductFacade */
        $aclEntityDummyProductFacade = $this->tester->getFacade();
        $aclEntityMetadataConfigTransfer = (new AclEntityMetadataConfigTransfer())
            ->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());

        // Act
        $aclEntityMetadataConfigTransfer = $aclEntityDummyProductFacade
            ->expandAclEntityMetadataConfigWithProductStoreRelation($aclEntityMetadataConfigTransfer);
        $aclEntityMetadataCollection = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();

        // Assert
        $this->assertInstanceOf(AclEntityMetadataConfigTransfer::class, $aclEntityMetadataConfigTransfer);
        $this->assertNotEmpty($aclEntityMetadataCollection);
        $this->assertSame(2, count($aclEntityMetadataCollection->getCollection()));
        $this->assertArrayHasKey(SpyProduct::class, $aclEntityMetadataCollection->getCollection());
        $this->assertArrayHasKey(SpyStore::class, $aclEntityMetadataCollection->getCollection());

        $this->assertNotEmpty($aclEntityMetadataCollection->getCollection()[SpyProduct::class]->getParent());
        $this->assertEquals(
            SpyProductAbstract::class,
            $aclEntityMetadataCollection->getCollection()[SpyProduct::class]->getParent()->getEntityName()
        );
        $this->assertEmpty($aclEntityMetadataCollection->getCollection()[SpyStore::class]->getParent());
    }

    /**
     * @return void
     */
    public function testExpandAclEntityMetadataCollectionWithProductCompositeRelation(): void
    {
        // Arrange
        /** @var \Spryker\Zed\AclEntityDummyProduct\Business\AclEntityDummyProductFacadeInterface $aclEntityDummyProductFacade */
        $aclEntityDummyProductFacade = $this->tester->getFacade();
        $aclEntityMetadataConfigTransfer = (new AclEntityMetadataConfigTransfer())
            ->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());

        // Act
        $aclEntityMetadataConfigTransfer = $aclEntityDummyProductFacade
            ->expandAclEntityMetadataConfigWithProductCompositeRelation($aclEntityMetadataConfigTransfer);
        $aclEntityMetadataCollection = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();

        // Assert
        $this->assertInstanceOf(AclEntityMetadataConfigTransfer::class, $aclEntityMetadataConfigTransfer);
        $this->assertNotEmpty($aclEntityMetadataCollection);
        $this->assertSame(2, count($aclEntityMetadataCollection->getCollection()));
        $this->assertArrayHasKey(SpyProductImage::class, $aclEntityMetadataCollection->getCollection());
        $this->assertEquals(
            SpyProductImageSet::class,
            $aclEntityMetadataCollection->getCollection()[SpyProductImage::class]->getParent()->getEntityName()
        );
        $this->assertEquals(
            SpyProductImageSetToProductImage::class,
            $aclEntityMetadataCollection->getCollection()[SpyProductImage::class]->getParent()->getConnection()->getPivotEntityName()
        );
        $this->assertArrayHasKey(
            SpyProductLocalizedAttributes::class,
            $aclEntityMetadataCollection->getCollection()
        );
    }
}
