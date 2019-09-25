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
 */
class LocaleLocalePlugin extends AbstractPlugin implements LocalePluginInterface
{
    public const REQUEST_URI = 'REQUEST_URI';

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
        return $this->buildLocaleTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function buildLocaleTransfer(): LocaleTransfer
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($this->getLocaleName());

        return $localeTransfer;
    }

    /**
     * @return string
     */
    protected function getLocaleName(): string
    {
        $currentLocale = $this->getClient()->getCurrentLocale();

        $requestUri = $this->getRequestUri();

        if ($requestUri) {
            $locales = $this->getFactory()->getStore()->getLocales();
            $localeCode = $this->extractLocaleCode($requestUri);
            if ($localeCode !== false && isset($locales[$localeCode])) {
                return $locales[$localeCode];
            }
        }

        return $currentLocale;
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
        $pathElements = explode('/', trim($requestUri, '/'));

        return $pathElements[0];
    }
}
