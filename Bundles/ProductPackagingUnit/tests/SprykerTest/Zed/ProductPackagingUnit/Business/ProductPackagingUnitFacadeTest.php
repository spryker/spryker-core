<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Generated\Shared\DataBuilder\ProductPackagingUnitTypeBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group Facade
 * @group ProductPackagingUnitFacadeTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitFacadeTest extends ProductPackagingUnitMocks
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInstallProductPackagingUnitTypesShouldPersistInfrastructuralPackagingUnitTypes(): void
    {
        // Assign
        $productPackagingUnitTypeTransfer = (new ProductPackagingUnitTypeBuilder())->build();
        $config = $this->createProductPackagingUnitConfigMock();
        $config->method('getInfrastructuralPackagingUnitTypes')
            ->willReturn([$productPackagingUnitTypeTransfer]);
        $factory = $this->createProductPackagingUnitBusinessFactoryMock($config);
        $facade = $this->createProductPackagingUnitFacadeMock($factory);

        // Action
        $facade->installProductPackagingUnitTypes();

        // Assert
        $productPackagingUnitTypeTransfer = $facade->getProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
        $this->assertNotNull($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
