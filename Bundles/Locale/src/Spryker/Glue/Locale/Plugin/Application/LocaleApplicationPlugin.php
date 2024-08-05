<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Locale\Plugin\Application;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\Config\Application\Environment as ApplicationEnvironment;
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
     * - Negotiates and provides application language ISO code.
     * - Sets the negotiated language ISO code to the container based on `Accept-Language` header.
     * - If the `Accept-Language` header is either empty or invalid, then language ISO code of the current store is used.
     * - If dynamic store is enabled, the store default language ISO code is used, otherwise the first of available store ISO codes.
     * - Throws exception {@link \Exception} while current store has no locale codes defined.
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
            $acceptLanguageHeader = $this->getAcceptLanguageHeader($container);
            $locale = $this->getFactory()
                ->createLanguageNegotiator()
                ->getLanguageIsoCode($acceptLanguageHeader);

            $this->setStoreCurrentLocale($locale);
            ApplicationEnvironment::initializeLocale($locale);

            return $locale;
        });

        return $container;
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @param string $locale
     *
     * @return void
     */
    protected function setStoreCurrentLocale(string $locale): void
    {
        /* Required by infrastructure, exists only for BC reasons with DMS mode. */
        if ($this->getFactory()->getStoreClient()->isDynamicStoreEnabled()) {
            return;
        }

        $this->getFactory()->getStore()->setCurrentLocale($locale);
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
