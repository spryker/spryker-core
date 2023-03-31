<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreGui\Communication\Table;

use Codeception\Test\Unit;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\StoreGui\Communication\StoreGuiCommunicationFactory;
use Spryker\Zed\StoreGui\Communication\Table\StoreTable;
use Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreGui
 * @group Communication
 * @group Table
 * @group StoreTableTest
 * Add your own group annotations below this line
 */
class StoreTableTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_FACTORY
     *
     * @var string
     */
    protected const SERVICE_FORM_FACTORY = 'form.factory';

    /**
     * @var string
     */
    protected const STORE_KEY_1 = 'DE';

    /**
     * @var string
     */
    protected const STORE_KEY_2 = 'AT';

    /**
     * @var \SprykerTest\Zed\ProductRelationGui\ProductRelationGuiCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\StoreGui\Communication\StoreGuiCommunicationFactory
     */
    protected $communicationFactory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->communicationFactory = new StoreGuiCommunicationFactory();

        $this->registerTwigServiceMock();
        $this->registerFormFactoryServiceMock();
    }

    /**
     * @dataProvider fetchDataShouldReturnStoresDataProvider
     *
     * @param bool $isDynamicMultiStoreEnabled
     *
     * @return void
     */
    public function testFetchDataShouldReturnStores(bool $isDynamicMultiStoreEnabled): void
    {
        // Arrange
        $store1 = $this->tester
            ->haveStore([
                'name' => static::STORE_KEY_1,
            ]);

        $store2 = $this->tester
            ->haveStore([
                'name' => static::STORE_KEY_2,
            ]);

        // Act
        $result = $this->getStoreTable($isDynamicMultiStoreEnabled)->fetchData();

        // Assert
        $storeIds = array_column($result, StoreTable::COL_ID_STORE);
        $this->assertNotEmpty($result);
        $this->assertContains($store1->getIdStore(), $storeIds);
        $this->assertContains($store2->getIdStore(), $storeIds);
    }

    /**
     * @return array
     */
    public function fetchDataShouldReturnStoresDataProvider(): array
    {
        return [
            [true],
            [false],
        ];
    }

    /**
     * @param bool $isDynamicMultiStoreEnabled
     *
     * @return \Spryker\Zed\StoreGui\Communication\Table\StoreTable
     */
    protected function getStoreTable(bool $isDynamicMultiStoreEnabled): StoreTable
    {
        $storeQuery = new SpyStoreQuery();

        return new StoreTableMock(
            $storeQuery,
            $this->communicationFactory->getStoreTableExpanderPlugins(),
            $this->getStoreFacadeMock($isDynamicMultiStoreEnabled),
        );
    }

    /**
     * @param bool $isDynamicMultiStoreEnabled
     *
     * @return \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getStoreFacadeMock(bool $isDynamicMultiStoreEnabled): StoreGuiToStoreFacadeInterface
    {
        $storeFacade = $this->getMockBuilder(StoreGuiToStoreFacadeInterface::class)->getMock();
        $storeFacade->method('isDynamicStoreEnabled')->willReturn($isDynamicMultiStoreEnabled);

        return $storeFacade;
    }

    /**
     * @return void
     */
    protected function registerTwigServiceMock(): void
    {
        $this->tester->getContainer()
            ->set(static::SERVICE_TWIG, $this->getTwigMock());
    }

    /**
     * @return void
     */
    protected function registerFormFactoryServiceMock(): void
    {
        $this->tester->getContainer()
            ->set(static::SERVICE_FORM_FACTORY, $this->getFormFactoryMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected function getTwigMock(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twigMock->method('render')
            ->willReturn('Fully rendered template');

        $twigMock->method('getLoader')
            ->willReturn($this->getChainLoader());

        return $twigMock;
    }

    /**
     * @return \Twig\Loader\LoaderInterface
     */
    protected function getChainLoader(): LoaderInterface
    {
        return new ChainLoader();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactoryMock(): FormFactoryInterface
    {
        $formFactoryMock = $this->getMockBuilder(FormFactoryInterface::class)->getMock();

        $formFactoryMock->method('create')
            ->willReturn($this->getFormMock());

        $formFactoryMock->method('createNamed')
            ->willReturn($this->getFormMock());

        return $formFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormInterface
     */
    protected function getFormMock(): FormInterface
    {
        $formMock = $this->getMockBuilder(FormInterface::class)->getMock();

        $formMock->method('createView')
            ->willReturn($this->getFormViewMock());

        return $formMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormView
     */
    protected function getFormViewMock(): FormView
    {
        return $this->getMockBuilder(FormView::class)->getMock();
    }
}
