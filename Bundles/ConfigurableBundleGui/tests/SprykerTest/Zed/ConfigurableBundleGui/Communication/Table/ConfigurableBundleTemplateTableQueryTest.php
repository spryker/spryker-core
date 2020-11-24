<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleGui\Communication\Table;

use Codeception\Test\Unit;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeBridge;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
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
 * @group ConfigurableBundleGui
 * @group Communication
 * @group Table
 * @group ConfigurableBundleTemplateTableQueryTest
 * Add your own group annotations below this line
 */
class ConfigurableBundleTemplateTableQueryTest extends Unit
{
    protected const CONFIGURABLE_BUNDLE_TEMPLATE_1 = 'BUNDLE-1';

    protected const CONFIGURABLE_BUNDLE_TEMPLATE_2 = 'BUNDLE-2';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_FACTORY
     */
    protected const SERVICE_FORM_FACTORY = 'form.factory';

    /**
     * @var \SprykerTest\Zed\ConfigurableBundleGui\ConfigurableBundleGuiCommunicationTester
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
    public function testFetchDataShouldReturnConfigurableBundlesTemplates(): void
    {
        // Arrange
        $configurableBundleTemplate1 = $this->tester->createConfigurableBundleTemplate(static::CONFIGURABLE_BUNDLE_TEMPLATE_1);
        $configurableBundleTemplate2 = $this->tester->createConfigurableBundleTemplate(static::CONFIGURABLE_BUNDLE_TEMPLATE_2);

        $configurableBundleQuery = SpyConfigurableBundleTemplateQuery::create();
        $tableMock = new ConfigurableBundleTemplateTableMock(
            $configurableBundleQuery,
            $this->getConfigurableBundleGuiToLocaleFacadeMock()
        );

        // Act
        $result = $tableMock->fetchData();

        // Assert
        $configurableBundleTemplateIdColName = str_replace(
            sprintf('%s.', SpyConfigurableBundleTemplateTableMap::TABLE_NAME),
            '',
            SpyConfigurableBundleTemplateTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE
        );
        $resultConfigurableBundleTemplateIds = array_column($result, $configurableBundleTemplateIdColName);
        $this->assertNotEmpty($result);
        $this->assertContains($configurableBundleTemplate1->getIdConfigurableBundleTemplate(), $resultConfigurableBundleTemplateIds);
        $this->assertContains($configurableBundleTemplate2->getIdConfigurableBundleTemplate(), $resultConfigurableBundleTemplateIds);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    protected function getConfigurableBundleGuiToLocaleFacadeMock(): ConfigurableBundleGuiToLocaleFacadeInterface
    {
        $configurableBundleGuiToLocaleFacadeMock = $this->getMockBuilder(ConfigurableBundleGuiToLocaleFacadeBridge::class)
            ->onlyMethods(['getCurrentLocale'])
            ->disableOriginalConstructor()
            ->getMock();

        $configurableBundleGuiToLocaleFacadeMock->expects($this->once())
            ->method('getCurrentLocale')
            ->willReturn($this->tester->getCurrentLocale());

        return $configurableBundleGuiToLocaleFacadeMock;
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
