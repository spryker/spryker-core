<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationGui\Communication\Table;

use Codeception\Test\Unit;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeBridge;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface;
use Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig;
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
 * @group ProductRelationGui
 * @group Communication
 * @group Table
 * @group ProductRelationTableTest
 * Add your own group annotations below this line
 */
class ProductRelationTableTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_FACTORY
     */
    protected const SERVICE_FORM_FACTORY = 'form.factory';

    protected const PRODUCT_RELATION_KEY_1 = 'PRODUCT_RELATION_KEY_1';
    protected const PRODUCT_RELATION_KEY_2 = 'PRODUCT_RELATION_KEY_2';
    protected const PRODUCT_RELATION_TYPE = 'PRODUCT_RELATION_TYPE';

    /**
     * @var \SprykerTest\Zed\ProductRelationGui\ProductRelationGuiCommunicationTester
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
    public function testFetchDataShouldReturnProductRelations(): void
    {
        // Arrange
        $productRelation1 = $this->tester->haveProductRelation(
            $this->tester->haveFullProduct()->getSku(),
            $this->tester->haveFullProduct()->getFkProductAbstract(),
            static::PRODUCT_RELATION_KEY_1,
            static::PRODUCT_RELATION_TYPE
        );

        $productRelation2 = $this->tester->haveProductRelation(
            $this->tester->haveFullProduct()->getSku(),
            $this->tester->haveFullProduct()->getFkProductAbstract(),
            static::PRODUCT_RELATION_KEY_2,
            static::PRODUCT_RELATION_TYPE
        );

        // Act
        $result = $this->getProductRelationTableMock()->fetchData();

        // Assert
        $resultProductRelationIds = array_column($result, SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION);
        $this->assertNotEmpty($result);
        $this->assertContains($productRelation1->getIdProductRelation(), $resultProductRelationIds);
        $this->assertContains($productRelation2->getIdProductRelation(), $resultProductRelationIds);
    }

    /**
     * @return \SprykerTest\Zed\ProductRelationGui\Communication\Table\ProductRelationTableMock
     */
    protected function getProductRelationTableMock(): ProductRelationTableMock
    {
        $productRelationQuery = new SpyProductRelationQuery();

        return new ProductRelationTableMock(
            $productRelationQuery,
            $this->getProductRelationGuiToProductFacadeMock(),
            $this->getProductRelationGuiConfigMock(),
            $this->getProductRelationGuiToLocaleFacadeMock()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface
     */
    protected function getProductRelationGuiToProductFacadeMock(): ProductRelationGuiToProductFacadeInterface
    {
        return $this->getMockBuilder(ProductRelationGuiToProductFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeInterface
     */
    protected function getProductRelationGuiConfigMock(): ProductRelationGuiConfig
    {
        return $this->getMockBuilder(ProductRelationGuiConfig::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface
     */
    protected function getProductRelationGuiToLocaleFacadeMock(): ProductRelationGuiToLocaleFacadeInterface
    {
        $productRelationGuiToLocaleFacadeMock = $this->getMockBuilder(ProductRelationGuiToLocaleFacadeBridge::class)
            ->onlyMethods(['getCurrentLocale'])
            ->disableOriginalConstructor()
            ->getMock();

        $productRelationGuiToLocaleFacadeMock->expects($this->once())
            ->method('getCurrentLocale')
            ->willReturn($this->tester->getCurrentLocale());

        return $productRelationGuiToLocaleFacadeMock;
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
