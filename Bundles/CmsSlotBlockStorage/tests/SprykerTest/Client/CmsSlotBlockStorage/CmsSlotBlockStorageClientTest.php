<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CmsSlotBlockStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Spryker\Client\CmsSlotBlockStorage\CmsSlotBlockStorageDependencyProvider;
use Spryker\Client\CmsSlotBlockStorage\Dependency\Client\CmsSlotBlockStorageToStorageClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CmsSlotBlockStorage
 * @group CmsSlotBlockStorageClientTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockStorageClientTest extends Unit
{
    protected const SLOT_KEY = 'cms-slot-key';
    protected const BLOCK_KEY = 'cms-block-key';
    protected const TEMPLATE_PATH = '@Home/index/home.twig';

    /**
     * @var \SprykerTest\Client\CmsSlotBlockStorage\CmsSlotBlockStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCmsSlotBlockCollectionReturnsCmsSlotBlockCollectionTransferWithCorrectData(): void
    {
        // Arrange
        $this->setStorageClientMock([
            CmsSlotBlockCollectionTransfer::CMS_SLOT_BLOCKS => [
                [
                    CmsSlotBlockTransfer::CMS_BLOCK_KEY => static::BLOCK_KEY,
                    CmsSlotBlockTransfer::CONDITIONS => [],
                ],
            ],
        ]);

        // Act
        $cmsSlotBlockCollectionTransfer = $this->tester->getCmsSlotBlockStorageClient()
            ->getCmsSlotBlockCollection(static::TEMPLATE_PATH, static::SLOT_KEY);

        // Assert
        $this->assertInstanceOf(CmsSlotBlockCollectionTransfer::class, $cmsSlotBlockCollectionTransfer);
        $this->assertCount(1, $cmsSlotBlockCollectionTransfer->getCmsSlotBlocks());
    }

    /**
     * @return void
     */
    public function testGetCmsSlotBlockCollectionReturnsEmptyCollectionWithIncorrectData(): void
    {
        // Arrange
        $this->setStorageClientMock(null);

        // Act
        $cmsSlotBlockCollectionTransfer = $this->tester->getCmsSlotBlockStorageClient()
            ->getCmsSlotBlockCollection(static::TEMPLATE_PATH, static::SLOT_KEY);

        // Assert
        $this->assertInstanceOf(CmsSlotBlockCollectionTransfer::class, $cmsSlotBlockCollectionTransfer);
        $this->assertCount(0, $cmsSlotBlockCollectionTransfer->getCmsSlotBlocks());
    }

    /**
     * @param array|null $cmsSlotBlockStorageData
     *
     * @return void
     */
    protected function setStorageClientMock(?array $cmsSlotBlockStorageData): void
    {
        $storageClientMock = $this->getMockBuilder(CmsSlotBlockStorageToStorageClientInterface::class)->getMock();
        $storageClientMock->expects($this->once())
            ->method('get')
            ->willReturn($cmsSlotBlockStorageData);
        $this->tester->setDependency(CmsSlotBlockStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);
    }
}
