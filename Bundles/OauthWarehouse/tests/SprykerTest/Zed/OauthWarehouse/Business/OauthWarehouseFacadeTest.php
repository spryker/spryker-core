<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthWarehouse\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\GlueRequestWarehouseTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToOauthFacadeBridge;
use Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToStockFacadeBridge;
use Spryker\Zed\OauthWarehouse\OauthWarehouseConfig;
use Spryker\Zed\OauthWarehouse\OauthWarehouseDependencyProvider;
use SprykerTest\Zed\OauthWarehouse\OauthWarehouseBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthWarehouse
 * @group Business
 * @group Facade
 * @group OauthWarehouseFacadeTest
 * Add your own group annotations below this line
 */
class OauthWarehouseFacadeTest extends Unit
{
    /**
     * @var int
     */
    protected const VALID_WAREHOUSE_ID = 1;

    /**
     * @var int
     */
    protected const NOT_VALID_WAREHOUSE_ID = 0;

    /**
     * @var string
     */
    protected const GLUE_REQUEST_USER = 'glueRequestUser';

    /**
     * @var string
     */
    protected const GLUE_REQUEST_WAREHOUSE = 'glueRequestWarehouse';

    /**
     * @var \SprykerTest\Zed\OauthWarehouse\OauthWarehouseBusinessTester
     */
    protected OauthWarehouseBusinessTester $tester;

    /**
     * @return void
     */
    public function testInstallWarehouseOauthDataShouldInstallWarehouseScopes(): void
    {
        // Arrange
        $expectedScopes = (new OauthWarehouseConfig())->getWarehouseScopes();
        $oauthFacadeMock = $this->getOauthFacadeMock();

        // Assert
        $oauthFacadeMock->expects($this->once())
            ->method('getScopesByIdentifiers')
            ->willReturn([]);
        $oauthFacadeMock->expects($this->exactly(count($expectedScopes)))
            ->method('saveScope')
            ->willReturn(new OauthScopeTransfer());

        // Act
        $this->tester->getFacade()->installWarehouseOauthData();
    }

    /**
     * @return void
     */
    public function testGetScopesShouldReturnWarehouseScopes(): void
    {
        // Arrange
        $oauthScopeRequestTransfer = new OauthScopeRequestTransfer();
        $expectedScopes = (new OauthWarehouseConfig())->getWarehouseScopes();

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getScopes($oauthScopeRequestTransfer);

        // Assert
        $this->assertCount(count($expectedScopes), $oauthScopeTransfers);
        foreach ($oauthScopeTransfers as $oauthScopeTransfer) {
            $this->assertTrue(in_array($oauthScopeTransfer->getIdentifier(), $expectedScopes));
        }
    }

    /**
     * @return void
     */
    public function testGetOauthWarehouseUserShouldReturnSuccessWhenWarehouseIsFound(): void
    {
        // Arrange
        $oauthUserTransfer = (new OauthUserTransfer())->setIdWarehouse(static::VALID_WAREHOUSE_ID);
        $stockFacadeMock = $this->getStockFacadeMock();

        // Assert
        $stockFacadeMock->expects($this->any())
            ->method('getStocksByStockCriteriaFilter')
            ->willReturn((new StockCollectionTransfer())->addStock(new StockTransfer()));

        // Act
        $oauthUserTransfer = $this->tester->getFacade()->getOauthWarehouseUser($oauthUserTransfer);

        // Assert
        $this->assertTrue($oauthUserTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testGetOauthWarehouseUserShouldReturnNotSuccessWhenWarehouseIsNotFound(): void
    {
        // Arrange
        $oauthUserTransfer = (new OauthUserTransfer())->setIdWarehouse(static::NOT_VALID_WAREHOUSE_ID);
        $stockFacadeMock = $this->getStockFacadeMock();

        // Assert
        $stockFacadeMock->expects($this->any())
            ->method('getStocksByStockCriteriaFilter')
            ->willReturn(new StockCollectionTransfer());

        // Act
        $oauthUserTransfer = $this->tester->getFacade()->getOauthWarehouseUser($oauthUserTransfer);

        // Assert
        $this->assertFalse($oauthUserTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testGetOauthWarehouseUserShouldReturnNotSuccessWhenIdWarehouseIsNotProvided(): void
    {
        // Arrange
        $oauthUserTransfer = (new OauthUserTransfer());

        // Act
        $oauthUserTransfer = $this->tester->getFacade()->getOauthWarehouseUser($oauthUserTransfer);

        // Assert
        $this->assertFalse($oauthUserTransfer->getIsSuccess());
    }

    /**
     * @dataProvider getAuthorizeTestData
     *
     * @param array $data
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testAuthorizeWithWarehouseRequest(array $data, bool $expectedResult): void
    {
        // Arrange
        $stockFacadeMock = $this->getStockFacadeMock();
        $stockCollectionTransfer = new StockCollectionTransfer();
        if (
            isset($data[static::GLUE_REQUEST_WAREHOUSE])
            && $data[static::GLUE_REQUEST_WAREHOUSE]->getIdWarehouse() === static::VALID_WAREHOUSE_ID
        ) {
            $stockCollectionTransfer->addStock(new StockTransfer());
        }

        // Assert
        $stockFacadeMock->expects($this->any())
            ->method('getStocksByStockCriteriaFilter')
            ->willReturn($stockCollectionTransfer);

        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->setEntity((new AuthorizationEntityTransfer())->setData($data));

        // Act
        $result = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return list<list<mixed>>
     */
    public function getAuthorizeTestData(): array
    {
        return [
            [
                [
                    'glueRequestWarehouse' => (new GlueRequestWarehouseTransfer())
                        ->setIdWarehouse(static::VALID_WAREHOUSE_ID),
                ],
                true,
            ],
            [
                [
                    'glueRequestWarehouse' => (new GlueRequestWarehouseTransfer())
                        ->setIdWarehouse(static::NOT_VALID_WAREHOUSE_ID),
                ],
                false,
            ],
            [
                [
                    'glueRequestUser' => new GlueRequestUserTransfer(),
                ],
                true,
            ],
            [
                [],
                false,
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToOauthFacadeBridge
     */
    protected function getOauthFacadeMock(): OauthWarehouseToOauthFacadeBridge
    {
        $oauthFacadeMock = $this->createMock(OauthWarehouseToOauthFacadeBridge::class);
        $this->tester->setDependency(
            OauthWarehouseDependencyProvider::FACADE_OAUTH,
            $oauthFacadeMock,
        );

        return $oauthFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToStockFacadeBridge
     */
    protected function getStockFacadeMock(): OauthWarehouseToStockFacadeBridge
    {
        $stockFacadeMock = $this->createMock(OauthWarehouseToStockFacadeBridge::class);
        $this->tester->setDependency(
            OauthWarehouseDependencyProvider::FACADE_STOCK,
            $stockFacadeMock,
        );

        return $stockFacadeMock;
    }
}
