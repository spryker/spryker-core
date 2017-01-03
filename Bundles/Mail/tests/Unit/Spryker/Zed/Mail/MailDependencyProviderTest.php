<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Mail;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Application\Communication\Application;
use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionAddInterface;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionAddInterface;
use Spryker\Zed\Mail\Dependency\Renderer\MailToRendererBridge;
use Spryker\Zed\Mail\MailDependencyProvider;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Mail
 * @group MailDependencyProviderTest
 */
class MailDependencyProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddProviderCollection()
    {
        $container = $this->getContainerWithProvidedDependencies();

        $this->assertInstanceOf(MailProviderCollectionAddInterface::class, $container[MailDependencyProvider::MAIL_PROVIDER_COLLECTION]);
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddMailCollection()
    {
        $container = $this->getContainerWithProvidedDependencies();

        $this->assertInstanceOf(MailTypeCollectionAddInterface::class, $container[MailDependencyProvider::MAIL_TYPE_COLLECTION]);
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependenciesShouldAddRenderer()
    {
        $this->addTwigToPimple();
        $container = $this->getContainerWithProvidedDependencies();

        $this->assertInstanceOf(MailToRendererBridge::class, $container[MailDependencyProvider::RENDERER]);
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainerWithProvidedDependencies()
    {
        $container = new Container();
        $mailDependencyProvider = new MailDependencyProvider();
        $mailDependencyProvider->provideBusinessLayerDependencies($container);

        return $container;
    }

    /**
     * @return void
     */
    protected function addTwigToPimple()
    {
        $pimple = new Pimple();
        $application = new Application();
        $application['twig'] = new Twig_Environment(new Twig_Loader_Filesystem());
        $pimple->setApplication($application);
    }

}
