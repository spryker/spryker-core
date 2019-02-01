<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Translator\TranslatorServiceFactory getFactory()
 */
class TranslatorService extends AbstractService implements TranslatorServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $id
     * @param array $parameters
     * @param string|null $domain
     * @param null $locale
     *
     * @return string
     */
    public function translate(string $id, array $parameters = [], string $domain = null, $locale = null): string
    {
        return $this->getFactory()->createTranslator()->trans($id, $parameters, $domain, $locale);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function generateTranslationCache(): void
    {
        $this->getFactory()->createCacheGenerator()->generateTranslationCache();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function cleanTranslationCache(): void
    {
        $this->getFactory()->createCacheCleaner()->cleanTranslationCache();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $message
     * @param string $localeName
     *
     * @return bool
     */
    public function hasTranslation(string $message, string $localeName): bool
    {
        return $this->getFactory()->createTranslationKeyManager()->hasTranslation($message, $localeName);
    }
}
