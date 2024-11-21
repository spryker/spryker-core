<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Customer\Plugin\EventDispatcher;

use Codeception\Test\Unit;
use Spryker\Shared\Customer\CustomerConfig;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Customer\Plugin\EventDispatcher\AnonymousIdSessionAssignEventDispatcherPlugin;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Customer
 * @group Plugin
 * @group EventDispatcher
 * @group AnonymousIdSessionAssignEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class AnonymousIdSessionAssignEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Yves\Customer\CustomerTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testThePluginSetsAnonymousIdToSessionWhenItIsEmpty(): void
    {
        // Arrange
        $plugin = new AnonymousIdSessionAssignEventDispatcherPlugin();

        // Act
        $event = $this->dispatchRequestEvent($plugin, [CustomerConfig::ANONYMOUS_SESSION_KEY => null]);

        // Assert
        $request = $event->getRequest();
        $this->assertNotEmpty($request->getSession()->get(CustomerConfig::ANONYMOUS_SESSION_KEY));
    }

    /**
     * @return void
     */
    public function testThePluginDoesNotSetAnonymousIdToSessionWhenItIsAlreadyInSession(): void
    {
        // Arrange
        $plugin = new AnonymousIdSessionAssignEventDispatcherPlugin();

        // Act
        $event = $this->dispatchRequestEvent($plugin, [CustomerConfig::ANONYMOUS_SESSION_KEY => '123']);

        // Assert
        $request = $event->getRequest();
        $this->assertSame('123', $request->getSession()->get(CustomerConfig::ANONYMOUS_SESSION_KEY), 'The anonymous id should not be changed.');
    }

    /**
     * @param \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface $plugin
     * @param array $sessionSeed
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function dispatchRequestEvent(EventDispatcherPluginInterface $plugin, array $sessionSeed = []): RequestEvent
    {
        $eventDispatcher = new EventDispatcher();
        $plugin->extend($eventDispatcher, $this->tester->getContainer());

        /** @var \Symfony\Component\HttpKernel\Event\RequestEvent $event */
        $event = $eventDispatcher->dispatch($this->tester->getRequestEvent($sessionSeed), KernelEvents::REQUEST);

        return $event;
    }
}
