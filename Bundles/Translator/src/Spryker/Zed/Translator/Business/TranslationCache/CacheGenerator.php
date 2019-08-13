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
     * @var \Spryker\Zed\Translator\Business\Translator\TranslatorInterface[]
     */
    protected $translatorSet;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface[] $translatorSet
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(array $translatorSet, Store $store)
    {
        $this->translatorSet = $translatorSet;
        $this->store = $store;
    }

    /**
     * @return void
     */
    public function generateTranslationCache(): void
    {
        foreach ($this->translatorSet as $translator) {
            $translator->getCatalogue();
        }
    }
}
