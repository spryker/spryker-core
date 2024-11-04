<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManager;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group DeleteOrphanPriceProductStoreEntitiesTest
 * Add your own group annotations below this line
 */
class DeleteOrphanPriceProductStoreEntitiesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testDeleteOrphanPriceProductStoreEntitiesNotFails(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();
        $priceProductBusinessFactory = (new PriceProductBusinessFactory());

        /** @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManager $priceProductEntityManagerMock */
        $priceProductEntityManagerMock = $this->getMockBuilder(PriceProductEntityManager::class)
            ->onlyMethods([
                'deletePriceProductStore',
            ])
            ->getMock();

        $priceProductBusinessFactory->setEntityManager($priceProductEntityManagerMock);
        $priceProductFacade->setFactory($priceProductBusinessFactory);

        // Act
        $priceProductFacade->deleteOrphanPriceProductStoreEntities();
    }
}
