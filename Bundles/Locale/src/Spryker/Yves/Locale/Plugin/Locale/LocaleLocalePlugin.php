<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Locale\Plugin\Locale;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 * @method \Spryker\Yves\Locale\LocaleFactory getFactory()
 * @method \Spryker\Yves\Locale\LocaleConfig getConfig()
 */
class LocaleLocalePlugin extends AbstractPlugin implements LocalePluginInterface
{
    /**
     * @var string
     */
    public const REQUEST_URI = 'REQUEST_URI';

    /**
     * @var string
     */
    protected const STORE = 'store';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleTransfer(ContainerInterface $container): LocaleTransfer
    {
        return $this->buildLocaleTransfer($container->get(static::STORE));
    }

    /**
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function buildLocaleTransfer(?string $storeName = null): LocaleTransfer
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($this->getLocaleName($storeName));

        return $localeTransfer;
    }

    /**
     * @param string|null $storeName
     *
     * @return string
     */
    protected function getLocaleName(?string $storeName = null): string
    {
        $requestUri = $this->getRequestUri();
        $locales = $this->getClient()->getLocales();
        if ($requestUri) {
            $localeCode = $this->extractLocaleCode($requestUri);
            if (isset($locales[$localeCode])) {
                return $locales[$localeCode];
            }
        }
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if ($storeName !== null && $this->getFactory()->getStoreClient()->isDynamicStoreEnabled()) {
            $storeTransfer = $this->getFactory()->getStoreClient()->getStoreByName($storeName);

            return $storeTransfer->getDefaultLocaleIsoCodeOrFail();
        }

        return (string)current($locales);
    }

    /**
     * @return string|null
     */
    protected function getRequestUri(): ?string
    {
        $requestUri = Request::createFromGlobals()
            ->server->get(static::REQUEST_URI);

        return $requestUri;
    }

    /**
     * @param string $requestUri
     *
     * @return string
     */
    protected function extractLocaleCode(string $requestUri): string
    {
        $urlPath = (string)parse_url(trim($requestUri, '/'), PHP_URL_PATH);
        $pathElements = explode('/', $urlPath);

        if ($this->getConfig()->isStoreRoutingEnabled() === true) {
            return $pathElements[$this->getConfig()->getLocaleCodeIndex()] ?? '';
        }

        return $pathElements[0];
    }
}
