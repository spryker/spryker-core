<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslationKeyManager;

use Symfony\Component\Translation\TranslatorBagInterface as SymfonyTranslatorBagInterface;

class TranslationKeyManager implements TranslationKeyManagerInterface
{
    /**
     * @var \Symfony\Component\Translation\TranslatorBagInterface
     */
    protected $translator;

    /**
     *
     * @param \Symfony\Component\Translation\TranslatorBagInterface $translator
     */
    public function __construct(SymfonyTranslatorBagInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $message
     * @param string $localeName
     *
     * @return bool
     */
    public function hasTranslation(string $message, string $localeName): bool
    {
        $catalogue = $this->translator->getCatalogue($localeName);

        return $catalogue->defines($message);
    }
}
