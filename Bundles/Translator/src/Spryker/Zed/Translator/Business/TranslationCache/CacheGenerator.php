<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslationCache;

use Spryker\Shared\Kernel\Store;

class CacheGenerator implements CacheGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Translator\Business\Translator\TranslatorInterface[]
     */
    protected $translatorCollection;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface[] $translatorCollection
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(array $translatorCollection, Store $store)
    {
        $this->translatorCollection = $translatorCollection;
        $this->store = $store;
    }

    /**
     * @return void
     */
    public function generateTranslationCache(): void
    {
        foreach ($this->translatorCollection as $translator) {
            $translator->getCatalogue();
        }
    }
}
