<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslationCache;

class CacheGenerator implements CacheGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Translator\Business\Translator\TranslatorInterface[]
     */
    protected $translatorCollection;

    /**
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface[] $translatorCollection
     */
    public function __construct(array $translatorCollection)
    {
        $this->translatorCollection = $translatorCollection;
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
