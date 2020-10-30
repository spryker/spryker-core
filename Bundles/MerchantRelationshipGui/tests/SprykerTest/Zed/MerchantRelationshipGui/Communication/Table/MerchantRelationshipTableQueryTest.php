<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipGui\Communication\Table;

use Codeception\Test\Unit;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Spryker\Zed\MerchantRelationshipGui\Communication\Table\MerchantRelationshipTableConstants;
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
 * @group MerchantRelationshipGui
 * @group Communication
 * @group Table
 * @group MerchantRelationshipTableQueryTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipTableQueryTest extends Unit
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
     * @var \SprykerTest\Zed\MerchantRelationship\MerchantRelationshipGuiCommunicationTester
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
    public function testFetchDataReturnsCorrectMerchantRelationshipData(): void
    {
        // Arrange
        $merchantRelationshipTransfer1 = $this->tester->createMerchantRelationship('test-merchant-relationship-1');
        $merchantRelationshipTransfer2 = $this->tester->createMerchantRelationship('test-merchant-relationship-1');

        $merchantRelationshipTableMock = new MerchantRelationshipTableMock(SpyMerchantRelationshipQuery::create());
        $merchantRelationshipTableMock->setTwig($this->getTwigMock());

        // Act
        $result = $merchantRelationshipTableMock->fetchData();

        // Assert
        $this->assertNotEmpty($result);
        $resultMerchantRelationshipIds = array_column($result, MerchantRelationshipTableConstants::COL_ID_MERCHANT_RELATIONSHIP);
        $this->assertContains($merchantRelationshipTransfer1->getIdMerchantRelationship(), $resultMerchantRelationshipIds);
        $this->assertContains($merchantRelationshipTransfer2->getIdMerchantRelationship(), $resultMerchantRelationshipIds);
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
