<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferGui\Communication\Table;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeBridge;
use Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepository;
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
 * @group ProductOfferGui
 * @group Communication
 * @group Table
 * @group ProductOfferTableQueryTest
 * Add your own group annotations below this line
 */
class ProductOfferTableQueryTest extends Unit
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
     * @var \SprykerTest\Zed\ProductOfferGui\ProductOfferGuiCommunicationTester
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
    public function testFetchDataShouldReturnProductOffers(): void
    {
        // Arrange
        $product = $this->tester->haveFullProduct();
        $productOfferSeedData = [
            ProductOfferTransfer::CONCRETE_SKU => $product->getSku(),
        ];
        $productOffer1 = $this->tester->haveProductOffer($productOfferSeedData);
        $productOffer2 = $this->tester->haveProductOffer($productOfferSeedData);

        $contentQuery = SpyProductOfferQuery::create();
        $productOfferGuiRepository = new ProductOfferGuiRepository();
        $tableMock = new ProductOfferTableMock(
            $contentQuery,
            $this->getProductOfferGuiToLocaleFacadeMock(),
            $this->getProductOfferGuiToProductOfferFacadeMock(),
            $productOfferGuiRepository,
            []
        );

        // Act
        $result = $tableMock->fetchData();

        // Assert
        $resultProductOffersIds = array_column($result, SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER);
        $this->assertNotEmpty($result);
        $this->assertContains($productOffer1->getIdProductOffer(), $resultProductOffersIds);
        $this->assertContains($productOffer2->getIdProductOffer(), $resultProductOffersIds);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToLocaleFacadeInterface
     */
    protected function getProductOfferGuiToLocaleFacadeMock(): ProductOfferGuiToLocaleFacadeInterface
    {
        $productOfferGuiToLocaleFacadeMock = $this->getMockBuilder(ProductOfferGuiToLocaleFacadeBridge::class)
            ->onlyMethods(['getCurrentLocale'])
            ->disableOriginalConstructor()
            ->getMock();

        $currentLocale = $this->tester->getLocator()
            ->locale()
            ->facade()
            ->getCurrentLocale();

        $productOfferGuiToLocaleFacadeMock->expects($this->once())
            ->method('getCurrentLocale')
            ->willReturn((new LocaleTransfer())->setIdLocale($currentLocale->getIdLocale()));

        return $productOfferGuiToLocaleFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOfferGui\Dependency\Facade\ProductOfferGuiToProductOfferFacadeInterface
     */
    protected function getProductOfferGuiToProductOfferFacadeMock(): ProductOfferGuiToProductOfferFacadeInterface
    {
        return $this->getMockBuilder(ProductOfferGuiToProductOfferFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
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
