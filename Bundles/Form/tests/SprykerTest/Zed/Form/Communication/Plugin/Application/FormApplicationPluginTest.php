<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Form\Communication\Plugin\Application;

use Codeception\Test\Unit;
use Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Form
 * @group Communication
 * @group Plugin
 * @group Application
 * @group FormApplicationPluginTest
 * Add your own group annotations below this line
 */
class FormApplicationPluginTest extends Unit
{
    use ContainerHelperTrait;

    protected const SERVICE_FORM_FACTORY = 'form.factory';
    protected const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';
    protected const SERVICE_FORM_FACTORY_ALIAS = 'FORM_FACTORY';

    /**
     * @var \SprykerTest\Zed\Form\FormCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFormApplicationPluginSetFormFactoryService(): void
    {
        //Arrange
        $container = $this->getContainerHelper()->getContainer();
        $plugin = new FormApplicationPlugin();

        //Act
        $container = $plugin->provide($container);

        //Arrange
        $this->assertTrue($container->has(static::SERVICE_FORM_FACTORY));
        $this->assertInstanceOf(FormFactoryInterface::class, $container->get(static::SERVICE_FORM_FACTORY));
    }

    /**
     * @return void
     */
    public function testFormApplicationSetFormFactoryServiceAlias(): void
    {
        //Arrange
        $container = $this->getContainerHelper()->getContainer();
        $plugin = new FormApplicationPlugin();

        //Act
        $container = $plugin->provide($container);

        //Arrange
        $this->assertTrue($container->has(static::SERVICE_FORM_FACTORY_ALIAS));
        $this->assertInstanceOf(FormFactoryInterface::class, $container->get(static::SERVICE_FORM_FACTORY_ALIAS));
    }

    /**
     * @return void
     */
    public function testFormApplicationSetCsrfProvider(): void
    {
        //Arrange
        $container = $this->getContainerHelper()->getContainer();
        $plugin = new FormApplicationPlugin();

        //Act
        $container = $plugin->provide($container);

        //Arrange
        $this->assertTrue($container->has(static::SERVICE_FORM_CSRF_PROVIDER));
        $this->assertInstanceOf(CsrfTokenManagerInterface::class, $container->get(static::SERVICE_FORM_CSRF_PROVIDER));
    }
}
