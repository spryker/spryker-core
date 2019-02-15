<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslationCache;

use Spryker\Shared\Kernel\Store;
use Symfony\Component\Translation\TranslatorBagInterface as SymfonyTranslatorBagInterface;

class CacheGenerator implements CacheGeneratorInterface
{
    /**
     * @var \Symfony\Component\Translation\TranslatorBagInterface
     */
    protected $translator;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Symfony\Component\Translation\TranslatorBagInterface $translator
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(SymfonyTranslatorBagInterface $translator, Store $store)
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
            $this->translator->getCatalogue($localeName);
        }
    }
}
