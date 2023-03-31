<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Locale\Plugin\Application;

use Exception;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\Config\Application\Environment as ApplicationEnvironment;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Glue\Locale\LocaleFactory getFactory()
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 */
class LocaleApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @uses \Spryker\Client\Locale\LocaleDependencyProvider::SERVICE_LOCALE
     *
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @uses \Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface::HEADER_ACCEPT_LANGUAGE
     *
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'accept-language';

    /**
     * @var string
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addLocale($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addLocale(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_LOCALE, function (ContainerInterface $container) {
            $localeName = $this->getLocale($container);
            $this->setStoreCurrentLocale($localeName);
            ApplicationEnvironment::initializeLocale($localeName);

            return $localeName;
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getLocale(ContainerInterface $container): string
    {
        $acceptLanguage = $this->getAcceptLanguageHeader($container);

        $allowedLocaleNames = $this->getClient()->getLocales();

        if ($allowedLocaleNames === []) {
            throw new Exception('Allowed locale names are missed');
        }

        if (!$acceptLanguage || !array_key_exists($acceptLanguage, $allowedLocaleNames)) {
            /** @phpstan-var string */
            return array_shift($allowedLocaleNames);
        }

        return $allowedLocaleNames[$acceptLanguage];
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @param string $localeName
     *
     * @return void
     */
    protected function setStoreCurrentLocale(string $localeName): void
    {
        if ($this->getFactory()->getStoreClient()->isDynamicStoreEnabled()) {
            return;
        }

        Store::getInstance()->setCurrentLocale($localeName);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return string|null
     */
    protected function getAcceptLanguageHeader(ContainerInterface $container): ?string
    {
        if ($this->getRequestStack($container)->getCurrentRequest() === null) {
            return null;
        }

        return $this->getRequestStack($container)
            ->getCurrentRequest()
            ->headers
            ->get(static::HEADER_ACCEPT_LANGUAGE);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStack(ContainerInterface $container): RequestStack
    {
        return $container->get(static::SERVICE_REQUEST_STACK);
    }
}
