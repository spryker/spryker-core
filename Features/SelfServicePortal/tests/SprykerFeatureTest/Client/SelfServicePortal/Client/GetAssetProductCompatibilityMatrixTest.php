<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Client\SelfServicePortal\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SspAssetStorageTransfer;
use SprykerFeature\Client\SelfServicePortal\Asset\Compatibility\AssetProductCompatibilityChecker;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClient;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Client
 * @group SelfServicePortal
 * @group Client
 * @group GetAssetProductCompatibilityMatrixTest
 * Add your own group annotations below this line
 */
class GetAssetProductCompatibilityMatrixTest extends Unit
{
    public function testReturnsEmptyWhenInputsEmpty(): void
    {
        $checkerMock = $this->createMock(AssetProductCompatibilityChecker::class);
        $factoryMock = $this->getMockBuilder(SelfServicePortalFactory::class)
            ->onlyMethods(['createAssetProductCompatibilityChecker'])
            ->getMock();
        $factoryMock->method('createAssetProductCompatibilityChecker')->willReturn($checkerMock);

        $clientMock = $this->getMockBuilder(SelfServicePortalClient::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $clientMock->method('getFactory')->willReturn($factoryMock);

        $this->assertSame([], $clientMock->getAssetProductCompatibilityMatrix([], ['SKU-1']));
        $this->assertSame([], $clientMock->getAssetProductCompatibilityMatrix(['AST--1'], []));
        $this->assertSame([], $clientMock->getAssetProductCompatibilityMatrix([], []));
    }

    public function testIteratesAssetsAndSkus(): void
    {
        $assetReferences = ['AST--1', 'AST--2'];
        $skus = ['SKU-1', 'SKU-2'];

        $checkerPartial = $this->getMockBuilder(AssetProductCompatibilityChecker::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getProductIdsBySkus', 'getAssetStoragesByReferences', 'checkCompatibility'])
            ->getMock();

        $checkerPartial->method('getProductIdsBySkus')->willReturn([
            'SKU-1' => 11,
            'SKU-2' => 22,
        ]);
        $checkerPartial->method('getAssetStoragesByReferences')->willReturn([
            'AST--1' => new SspAssetStorageTransfer(),
            'AST--2' => new SspAssetStorageTransfer(),
        ]);
        $checkerPartial->expects($this->exactly(4))
            ->method('checkCompatibility')
            ->willReturnOnConsecutiveCalls(true, false, false, true);

        $factoryMock = $this->getMockBuilder(SelfServicePortalFactory::class)
            ->onlyMethods(['createAssetProductCompatibilityChecker'])
            ->getMock();
        $factoryMock->method('createAssetProductCompatibilityChecker')->willReturn($checkerPartial);

        $clientMock = $this->getMockBuilder(SelfServicePortalClient::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $clientMock->method('getFactory')->willReturn($factoryMock);

        $matrix = $clientMock->getAssetProductCompatibilityMatrix($assetReferences, $skus);

        $this->assertSame([
            'AST--1' => ['SKU-1' => true, 'SKU-2' => false],
            'AST--2' => ['SKU-1' => false, 'SKU-2' => true],
        ], $matrix);
    }

    public function testHandlesMissingSkuMapping(): void
    {
        $assetReferences = ['AST--1'];
        $skus = ['SKU-UNKNOWN'];

        $checkerPartial = $this->getMockBuilder(AssetProductCompatibilityChecker::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getProductIdsBySkus', 'getAssetStoragesByReferences', 'checkCompatibility'])
            ->getMock();

        $checkerPartial->method('getProductIdsBySkus')->willReturn([]);
        $checkerPartial->method('getAssetStoragesByReferences')->willReturn([
            'AST--1' => new SspAssetStorageTransfer(),
        ]);
        $checkerPartial->expects($this->once())
            ->method('checkCompatibility')
            ->willReturn(false);

        $factoryMock = $this->getMockBuilder(SelfServicePortalFactory::class)
            ->onlyMethods(['createAssetProductCompatibilityChecker'])
            ->getMock();
        $factoryMock->method('createAssetProductCompatibilityChecker')->willReturn($checkerPartial);

        $clientMock = $this->getMockBuilder(SelfServicePortalClient::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $clientMock->method('getFactory')->willReturn($factoryMock);

        $matrix = $clientMock->getAssetProductCompatibilityMatrix($assetReferences, $skus);

        $this->assertSame([
            'AST--1' => ['SKU-UNKNOWN' => false],
        ], $matrix);
    }
}
