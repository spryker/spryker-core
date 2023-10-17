<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Locale\Plugin\Application;

use Codeception\Test\Unit;
use Spryker\Client\Locale\LocaleClient;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Glue\Locale\Plugin\Application\LocaleApplicationPlugin;
use Spryker\Service\Container\ContainerInterface;
use SprykerTest\Glue\Locale\LocaleGlueTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Locale
 * @group Plugin
 * @group Application
 * @group LocaleApplicationPluginTest
 * Add your own group annotations below this line
 */
class LocaleApplicationPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_KEY_DE = 'de';

    /**
     * @var array<string, string>
     */
    protected const LOCALES = [
        self::LOCALE_KEY_DE => 'de_DE',
    ];

    /**
     * @var string
     */
    protected const APPLICATION_REQUEST_STACK = 'request_stack';

    /**
     * @var string
     */
    protected const APPLICATION_LOCALE = 'locale';

    /**
     * @var \SprykerTest\Glue\Locale\LocaleGlueTester
     */
    protected LocaleGlueTester $tester;

    /**
     * @return void
     */
    public function testProvideShouldAddLocaleToContainerWhileLocaleEqualsAcceptHeaderLanguage(): void
    {
        // Arrange
        $requestStack = $this->createRequestStack(static::LOCALES[static::LOCALE_KEY_DE]);
        $container = $this->createContainer($requestStack);
        $localeApplicationPlugin = new LocaleApplicationPlugin();

        $this->tester->mockFactoryMethod('getClient', $this->createLocaleClientMock(static::LOCALES));

        $localeApplicationPlugin->setFactory($this->tester->getFactory());

        // Act
        $container = $localeApplicationPlugin->provide($container);

        // Assert
        $this->assertTrue($container->has(static::APPLICATION_LOCALE));
        $this->assertSame(static::LOCALES[static::LOCALE_KEY_DE], $container->get(static::APPLICATION_LOCALE));
    }

    /**
     * @return void
     */
    public function testProvideShouldAddDefaultLocaleToContainerWhileEmptyAcceptHeaderLanguage(): void
    {
        // Arrange
        $requestStack = $this->createRequestStack('');
        $container = $this->createContainer($requestStack);
        $localeApplicationPlugin = new LocaleApplicationPlugin();

        $this->tester->mockFactoryMethod('getClient', $this->createLocaleClientMock(static::LOCALES));

        $localeApplicationPlugin->setFactory($this->tester->getFactory());

        // Act
        $container = $localeApplicationPlugin->provide($container);

        // Assert
        $this->assertTrue($container->has(static::APPLICATION_LOCALE));
        $this->assertSame(static::LOCALES[static::LOCALE_KEY_DE], $container->get(static::APPLICATION_LOCALE));
    }

    /**
     * @return void
     */
    public function testProvideShouldAddLocaleToContainerWhileAcceptLanguageGotNegotiated(): void
    {
        // Arrange
        $requestStack = $this->createRequestStack('de;q=0.8, en;q=0.2');
        $container = $this->createContainer($requestStack);
        $localeApplicationPlugin = new LocaleApplicationPlugin();

        $this->tester->mockFactoryMethod('getClient', $this->createLocaleClientMock(static::LOCALES));

        $localeApplicationPlugin->setFactory($this->tester->getFactory());

        // Act
        $container = $localeApplicationPlugin->provide($container);

        // Assert
        $this->assertTrue($container->has(static::APPLICATION_LOCALE));
        $this->assertSame(static::LOCALES[static::LOCALE_KEY_DE], $container->get(static::APPLICATION_LOCALE));
    }

    /**
     * @return void
     */
    public function testProvideShouldAddDefaultLocaleToContainerWhileAcceptLanguageGotNotNegotiated(): void
    {
        // Arrange
        $requestStack = $this->createRequestStack('foobar');
        $container = $this->createContainer($requestStack);
        $localeApplicationPlugin = new LocaleApplicationPlugin();

        $this->tester->mockFactoryMethod('getClient', $this->createLocaleClientMock(static::LOCALES));

        $localeApplicationPlugin->setFactory($this->tester->getFactory());

        // Act
        $container = $localeApplicationPlugin->provide($container);

        // Assert
        $this->assertTrue($container->has(static::APPLICATION_LOCALE));
        $this->assertSame(static::LOCALES[static::LOCALE_KEY_DE], $container->get(static::APPLICATION_LOCALE));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function createContainer(RequestStack $requestStack): ContainerInterface
    {
        $container = $this->tester->getContainer();
        $container->set(static::APPLICATION_REQUEST_STACK, $requestStack);

        return $container;
    }

    /**
     * @param string $headerAcceptLanguage
     *
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function createRequestStack(string $headerAcceptLanguage): RequestStack
    {
        $request = Request::create('/');
        $request->headers->set('accept-language', $headerAcceptLanguage);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }

    /**
     * @param array<string, string> $currentStoreLocaleCodes
     *
     * @return \Spryker\Client\Locale\LocaleClientInterface
     */
    protected function createLocaleClientMock(array $currentStoreLocaleCodes): LocaleClientInterface
    {
        $localeClientMock = $this->getMockBuilder(LocaleClient::class)->getMock();
        $localeClientMock->method('getLocales')->willReturn($currentStoreLocaleCodes);

        return $localeClientMock;
    }
}
