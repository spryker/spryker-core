<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;
use Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory;
use Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group InstallProductPackagingUnitTypesTest
 * Add your own group annotations below this line
 */
class InstallProductPackagingUnitTypesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testInstallProductPackagingUnitTypesShouldPersistInfrastructuralPackagingUnitTypes(): void
    {
        // Arrange
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())->build();
        $config = $this->createProductPackagingUnitConfigMock();
        $config->method('getInfrastructuralPackagingUnitTypes')
            ->willReturn([$productPackagingUnitTypeTransfer]);
        $factory = $this->createProductPackagingUnitBusinessFactoryMock($config);
        $facade = $this->createProductPackagingUnitFacadeMock($factory);

        // Act
        $facade->installProductPackagingUnitTypes();

        // Assert
        $productPackagingUnitTypeTransfer = $this->tester->getFacade()->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
        $this->assertNotNull($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig
     */
    protected function createProductPackagingUnitConfigMock(): ProductPackagingUnitConfig
    {
        return $this->getMockBuilder(ProductPackagingUnitConfig::class)
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig|\PHPUnit\Framework\MockObject\MockObject|null $config
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory
     */
    protected function createProductPackagingUnitBusinessFactoryMock(?ProductPackagingUnitConfig $config = null): ProductPackagingUnitBusinessFactory
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory $mockObject */
        $mockObject = $this->getMockBuilder(ProductPackagingUnitBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($config !== null) {
            $mockObject->setConfig($config);
        }

        return $mockObject;
    }

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory|\PHPUnit\Framework\MockObject\MockObject|null $factory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade
     */
    protected function createProductPackagingUnitFacadeMock(?ProductPackagingUnitBusinessFactory $factory = null): ProductPackagingUnitFacade
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade $mockObject */
        $mockObject = $this->getMockBuilder(ProductPackagingUnitFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($factory !== null) {
            $mockObject->setFactory($factory);
        }

        return $mockObject;
    }
}
