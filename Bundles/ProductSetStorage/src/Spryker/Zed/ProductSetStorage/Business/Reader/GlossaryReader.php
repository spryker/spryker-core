<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business\Reader;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToGlossaryFacadeInterface;

class GlossaryReader implements GlossaryReaderInterface
{
    /**
     * @param \Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(protected ProductSetStorageToGlossaryFacadeInterface $glossaryFacade)
    {
    }

    /**
     * @param list<string> $glossaryKeys
     * @param string $localeName
     *
     * @return array<string, string|null>
     */
    public function getTranslationsIndexedByGlossaryKey(array $glossaryKeys, string $localeName): array
    {
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers(
            $glossaryKeys,
            [(new LocaleTransfer())->setLocaleName($localeName)],
        );

        $translationsIndexedByGlossaryKey = [];

        foreach ($translationTransfers as $translationTransfer) {
            if (!$translationTransfer->getIsActive()) {
                continue;
            }

            $translationsIndexedByGlossaryKey[$translationTransfer->getGlossaryKeyOrFail()->getKey()] = $translationTransfer->getValue();
        }

        return $translationsIndexedByGlossaryKey;
    }
}
