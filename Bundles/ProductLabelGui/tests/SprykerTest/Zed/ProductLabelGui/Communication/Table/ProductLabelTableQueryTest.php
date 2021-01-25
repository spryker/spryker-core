<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelGui\Communication\Table;

use Codeception\Test\Unit;
use Spryker\Zed\ProductLabelGui\Communication\Table\ProductLabelTable;
use Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainer;
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
 * @group ProductLabelGui
 * @group Communication
 * @group Table
 * @group ProductLabelTableQueryTest
 * Add your own group annotations below this line
 */
class ProductLabelTableQueryTest extends Unit
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
     * @var \SprykerTest\Zed\ProductLabelGui\ProductLabelGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductLabelTableIsEmpty();
        $this->registerTwigServiceMock();
        $this->registerFormFactoryServiceMock();
    }

    /**
     * @return void
     */
    public function testFetchDataReturnsCorrectProductLabelData(): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $productLabelTransfer1 = $this->tester->haveProductLabel();
        $productLabelTransfer2 = $this->tester->haveProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer1->getIdProductLabel(), $productAbstractTransfer1->getIdProductAbstract());
        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer1->getIdProductLabel(), $productAbstractTransfer2->getIdProductAbstract());
        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer2->getIdProductLabel(), $productAbstractTransfer1->getIdProductAbstract());

        $productLabelTableMock = new ProductLabelTableMock(new ProductLabelGuiQueryContainer());
        $productLabelTableMock->setTwig($this->getTwigMock());

        // Act
        $result = $productLabelTableMock->fetchData();

        // Assert
        $this->assertCount(2, $result);
        $this->assertEquals($productLabelTransfer1->getIdProductLabel(), $result[0][ProductLabelTable::COL_ID_PRODUCT_LABEL]);
        $this->assertEquals($productLabelTransfer2->getIdProductLabel(), $result[1][ProductLabelTable::COL_ID_PRODUCT_LABEL]);
        $this->assertEquals(2, $result[0][ProductLabelTable::COL_ABSTRACT_PRODUCT_RELATION_COUNT]);
        $this->assertEquals(1, $result[1][ProductLabelTable::COL_ABSTRACT_PRODUCT_RELATION_COUNT]);
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
