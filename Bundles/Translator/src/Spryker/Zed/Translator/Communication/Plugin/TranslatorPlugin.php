<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication\Plugin;

use Spryker\Shared\TranslatorExtension\Dependency\Plugin\TranslatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 * @method \Spryker\Zed\Translator\Communication\TranslatorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 */
class TranslatorPlugin extends AbstractPlugin implements TranslatorPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $id
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null): string
    {
        return $this->getFacade()->trans($id, $parameters, $domain, $locale);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string
     */
    public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null): string
    {
        return $this->getFacade()->transChoice($id, $number, $parameters, $domain, $locale);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @see \Symfony\Contracts\Translation\TranslatorInterface
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $locale
     *
     * @@return void
     */
    public function setLocale($locale): void
    {
        $this->getFacade()->setLocale($locale);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @see \Symfony\Contracts\Translation\TranslatorInterface
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @return string The locale
     */
    public function getLocale(): string
    {
        return $this->getFacade()->getLocale();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $keyName
     * @param string $locale
     *
     * @return bool
     */
    public function has(string $keyName, string $locale): bool
    {
        return $this->getFacade()->has($keyName, $locale);
    }
}
