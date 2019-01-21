<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslationCache;

use Spryker\Service\Translator\Translator\TranslatorCacheGeneratorInterface;
use Spryker\Shared\Kernel\Store;

class CacheGenerator implements CacheGeneratorInterface
{
    /**
     * @var \Spryker\Service\Translator\Translator\TranslatorCacheGeneratorInterface
     */
    protected $translator;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Service\Translator\Translator\TranslatorCacheGeneratorInterface $translator
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(TranslatorCacheGeneratorInterface $translator, Store $store)
    {
        $this->translator = $translator;
        $this->store = $store;
    }

    /**
     * @return void
     */
    public function generateTranslationCache(): void
    {
        foreach ($this->store->getLocales() as $localeName) {
            $this->translator->generateCacheForLocale($localeName);
        }
    }
}
