<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Business\Reader;

use Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface;

class TranslationReader implements TranslationReaderInterface
{
    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface
     */
    protected $glossaryRepository;

    /**
     * @param \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface $glossaryRepository
     */
    public function __construct(GlossaryRepositoryInterface $glossaryRepository)
    {
        $this->glossaryRepository = $glossaryRepository;
    }

    /**
     * @param string $glossaryKey
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    public function getTranslationsByGlossaryKeyAndLocaleTransfers(string $glossaryKey, array $localeTransfers): array
    {
        if (!$localeTransfers) {
            return [];
        }

        return $this->glossaryRepository->getTranslationsByGlossaryKeyAndLocaleIsoCodes($glossaryKey, $this->getLocaleIsoCodes($localeTransfers));
    }

    /**
     * @param string[] $glossaryKeys
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    public function getTranslationsByGlossaryKeysAndLocaleTransfers(array $glossaryKeys, array $localeTransfers): array
    {
        if (!$localeTransfers) {
            return [];
        }

        return $this->glossaryRepository->getTranslationsByGlossaryKeysAndLocaleIsoCodes($glossaryKeys, $this->getLocaleIsoCodes($localeTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return string[]
     */
    protected function getLocaleIsoCodes(array $localeTransfers): array
    {
        $localeIsoCodes = [];

        foreach ($localeTransfers as $localeTransfer) {
            $localeIsoCodes[] = $localeTransfer->getLocaleName();
        }

        return $localeIsoCodes;
    }
}
