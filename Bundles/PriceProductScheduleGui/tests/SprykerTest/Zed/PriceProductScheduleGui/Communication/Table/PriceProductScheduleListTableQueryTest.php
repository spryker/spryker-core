<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductScheduleGui\Communication\Table;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;
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
 * @group PriceProductScheduleGui
 * @group Communication
 * @group Table
 * @group PriceProductScheduleListTableQueryTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleListTableQueryTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_FACTORY
     */
    protected const SERVICE_FORM_FACTORY = 'form.factory';

    /**
     * @uses \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleListTable::COL_NUMBER_OF_PRICES
     */
    protected const COL_NUMBER_OF_PRICES = 'number_of_prices';

    /**
     * @uses \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleListTable::COL_NUMBER_OF_PRODUCTS
     */
    protected const COL_NUMBER_OF_PRODUCTS = 'number_of_products';

    /**
     * @var \SprykerTest\Zed\PriceProductScheduleGui\PriceProductScheduleGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registerTwigServiceMock();
        $this->registerFormFactoryServiceMock();
    }

    /**
     * @return void
     */
    public function testFetchDataReturnsCorrectPriceProductScheduleData(): void
    {
        // Arrange
        $priceProductScheduleListTransfer1 = $this->tester->havePriceProductScheduleList();
        $priceProductScheduleListTransfer2 = $this->tester->havePriceProductScheduleList();

        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer = $this->tester->havePriceType();
        $idCurrency = $this->tester->haveCurrency();
        $storeTransfer = $this->tester->getCurrentStore();

        $priceProductScheduleData = [
            PriceProductScheduleTransfer::PRICE_PRODUCT => $this->getPriceProductData($priceTypeTransfer, $idCurrency, $storeTransfer->getIdStore()),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days')),
        ];
        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::ID_PRODUCT_ABSTRACT] = $productConcreteTransfer->getFkProductAbstract();
        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE][MoneyValueTransfer::FK_CURRENCY] = $idCurrency;

        $priceProductScheduleData1 = $priceProductScheduleData2 = $priceProductScheduleData;
        $priceProductScheduleData1[PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST] = $priceProductScheduleListTransfer1;
        $priceProductScheduleData2[PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST] = $priceProductScheduleListTransfer2;

        $this->tester->havePriceProductSchedule($priceProductScheduleData1);
        $this->tester->havePriceProductSchedule($priceProductScheduleData1);
        $this->tester->havePriceProductSchedule($priceProductScheduleData2);
        $this->tester->havePriceProductSchedule($priceProductScheduleData2);

        $priceProductScheduleListTableMock = new PriceProductScheduleListTableMock(
            SpyPriceProductScheduleListQuery::create(),
            $this->getPriceProductScheduleGuiToStoreFacadeMock()
        );
        $priceProductScheduleListTableMock->setTwig($this->getTwigMock());

        // Act
        $result = $priceProductScheduleListTableMock->fetchData();

        // Assert
        $this->assertNotEmpty($result);
        $resultPriceProductScheduleListIds = array_keys($result);
        $this->assertContains($priceProductScheduleListTransfer1->getIdPriceProductScheduleList(), $resultPriceProductScheduleListIds);
        $this->assertContains($priceProductScheduleListTransfer2->getIdPriceProductScheduleList(), $resultPriceProductScheduleListIds);
        $this->assertEquals(2, $result[$priceProductScheduleListTransfer1->getIdPriceProductScheduleList()][static::COL_NUMBER_OF_PRICES]);
        $this->assertEquals(1, $result[$priceProductScheduleListTransfer1->getIdPriceProductScheduleList()][static::COL_NUMBER_OF_PRODUCTS]);
        $this->assertEquals(2, $result[$priceProductScheduleListTransfer2->getIdPriceProductScheduleList()][static::COL_NUMBER_OF_PRICES]);
        $this->assertEquals(1, $result[$priceProductScheduleListTransfer2->getIdPriceProductScheduleList()][static::COL_NUMBER_OF_PRODUCTS]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return array
     */
    protected function getPriceProductData(PriceTypeTransfer $priceTypeTransfer, int $idCurrency, int $idStore): array
    {
        return [
            PriceProductTransfer::PRICE_TYPE => [
                PriceTypeTransfer::NAME => $priceTypeTransfer->getName(),
                PriceTypeTransfer::ID_PRICE_TYPE => $priceTypeTransfer->getIdPriceType(),
            ],
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::FK_STORE => $idStore,
                MoneyValueTransfer::FK_CURRENCY => $idCurrency,
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected function getPriceProductScheduleGuiToStoreFacadeMock(): PriceProductScheduleGuiToStoreFacadeInterface
    {
        $storeFacadeMock = $this->getMockBuilder(PriceProductScheduleGuiToStoreFacadeInterface::class)->getMock();
        $storeFacadeMock->method('getCurrentStore')
            ->willReturn($this->tester->getCurrentStore());

        return $storeFacadeMock;
    }

    /**
     * @return void
     */
    protected function registerTwigServiceMock(): void
    {
        $this->tester->getContainer()->set(static::SERVICE_TWIG, $this->getTwigMock());
    }

    /**
     * @return void
     */
    protected function registerFormFactoryServiceMock(): void
    {
        $this->tester->getContainer()->set(static::SERVICE_FORM_FACTORY, $this->getFormFactoryMock());
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
        $twigMock->method('getLoader')->willReturn($this->getChainLoader());

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
        $formFactoryMock->method('create')->willReturn($this->getFormMock());
        $formFactoryMock->method('createNamed')->willReturn($this->getFormMock());

        return $formFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormInterface
     */
    protected function getFormMock(): FormInterface
    {
        $formMock = $this->getMockBuilder(FormInterface::class)->getMock();
        $formMock->method('createView')->willReturn($this->getFormViewMock());

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
