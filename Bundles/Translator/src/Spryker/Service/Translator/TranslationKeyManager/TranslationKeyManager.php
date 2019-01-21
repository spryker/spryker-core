<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslationKeyManager;

use Spryker\Service\Translator\Translator\TranslatorCacheGeneratorInterface;

class TranslationKeyManager implements TranslationKeyManagerInterface
{
    /**
     * @var \Spryker\Service\Translator\Translator\TranslatorCacheGeneratorInterface
     */
    protected $translator;

    /**
     * @param \Spryker\Service\Translator\Translator\TranslatorCacheGeneratorInterface $translator
     */
    public function __construct(TranslatorCacheGeneratorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName): bool
    {
        $locale = $this->translator->getLocale();
        $catalogue = $this->translator->getCatalogue($locale);

        return $catalogue->defines($keyName);
    }
}
