<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig as ZedPriceProductConfig;
use Spryker\Zed\PriceProduct\PriceProductDependencyProvider;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group PersistPriceProductStoreTest
 * Add your own group annotations below this line
 */
class PersistPriceProductStoreTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Store\StoreDependencyProvider::STORE_CURRENT
     *
     * @var string
     */
    protected const STORE_CURRENT = 'STORE_CURRENT';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const DEFAULT_LOCALE = 'en';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testPersistPriceProductStore(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->createProductWithAmount(50, 40);

        // Act
        $actualResult = $this->tester->getFacade()->persistPriceProductStore($priceProductTransfer);

        // Assert
        $this->assertEquals($priceProductTransfer, $actualResult);
    }

    /**
     * @dataProvider orphanPriceProductStoreDataProvider
     *
     * @param bool $controlRemovalByPluginStack
     * @param bool $orphanStorePricesRemovalEnabled
     * @param bool $expectedOrphans
     *
     * @return void
     */
    public function testOrphanPriceProductStoreAreDeletedForConcreteProducts(
        bool $controlRemovalByPluginStack,
        bool $orphanStorePricesRemovalEnabled,
        bool $expectedOrphans
    ): void {
        // Arrange
        $priceProductFacade = $this->setupFacadeForOrphanPriceProductStoreRemovalTest(
            $controlRemovalByPluginStack,
            $orphanStorePricesRemovalEnabled,
        );

        $priceTypeTransfer = $this->tester->havePriceType();
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->createPriceProductTransfer(
            $productConcreteTransfer,
            $priceTypeTransfer,
            10,
            9,
            PriceProductBusinessTester::EUR_ISO_CODE,
        );
        $priceProductTransfer->setFkPriceType($priceTypeTransfer->getIdPriceType());

        // Act
        $priceProductTransfer = $priceProductFacade->persistPriceProductStore($priceProductTransfer);
        $priceProductTransfer->getMoneyValue()->setGrossAmount(8);
        $priceProductTransfer = $priceProductFacade->persistPriceProductStore($priceProductTransfer);
        $priceProductEntity = (new SpyPriceProductQuery())
            ->filterByIdPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->findOne();

        // Assert
        $hasOrphanPriceProductStore = $priceProductEntity->getPriceProductStores()->count() > 1;
        $this->assertSame(
            $hasOrphanPriceProductStore,
            $expectedOrphans,
        );
    }

    /**
     * @return array<string, array<bool>>
     */
    public function orphanPriceProductStoreDataProvider(): array
    {
        return [
            'Testing that orphans are deleted when orphan removal is enabled by plugin stack' => [true, true, false],
            'Testing that orphans are kept when orphan removal is disabled by plugin stack' => [true, false, true],
            'Testing that orphans are deleted when orphan removal is enabled by config' => [false, true, false],
            'Testing that orphans are kept when orphan removal is disabled by config' => [false, false, true],
        ];
    }

    /**
     * @param bool $controlRemovalByPluginStack
     * @param bool $orphanStorePricesRemovalEnabled
     *
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function setupFacadeForOrphanPriceProductStoreRemovalTest(
        bool $controlRemovalByPluginStack,
        bool $orphanStorePricesRemovalEnabled
    ): PriceProductFacadeInterface {
        if ($controlRemovalByPluginStack) {
            return $this->setupFacadeForOrphanPriceProductStoreRemovalTestWithControlByPluginStack($orphanStorePricesRemovalEnabled);
        }

        return $this->setupFacadeForOrphanPriceProductStoreRemovalTestWithControlByConfig($orphanStorePricesRemovalEnabled);
    }

    /**
     * @param bool $orphanStorePricesRemovalEnabled
     *
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function setupFacadeForOrphanPriceProductStoreRemovalTestWithControlByPluginStack(
        bool $orphanStorePricesRemovalEnabled
    ): PriceProductFacadeInterface {
        $orphanPriceProductStoreRemovalVoterPluginMock = $this->getMockBuilder(OrphanPriceProductStoreRemovalVoterPluginInterface::class)
            ->onlyMethods([
                'isRemovalEnabled',
            ])
            ->getMock();
        $orphanPriceProductStoreRemovalVoterPluginMock
            ->method('isRemovalEnabled')
            ->willReturn($orphanStorePricesRemovalEnabled);
        $this->tester->setDependency(PriceProductDependencyProvider::PLUGINS_ORPHAN_PRICE_PRODUCT_STORE_REMOVAL_VOTER, [
            $orphanPriceProductStoreRemovalVoterPluginMock,
        ]);

        return $this->getPriceProductFacade();
    }

    /**
     * @param bool $orphanStorePricesRemovalEnabled
     *
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function setupFacadeForOrphanPriceProductStoreRemovalTestWithControlByConfig(
        bool $orphanStorePricesRemovalEnabled
    ): PriceProductFacadeInterface {
        $configMock = $this->createMock(ZedPriceProductConfig::class);
        $configMock->method('getIsDeleteOrphanStorePricesOnSaveEnabled')->willReturn($orphanStorePricesRemovalEnabled);

        $priceProductBusinessFactory = new PriceProductBusinessFactory();
        $priceProductBusinessFactory->setConfig($configMock);

        return $this->getPriceProductFacade()
            ->setFactory($priceProductBusinessFactory);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return new PriceProductFacade();
    }
}
