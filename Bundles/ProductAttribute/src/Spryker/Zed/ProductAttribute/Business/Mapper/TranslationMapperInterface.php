<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Mapper;

interface TranslationMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\TranslationTransfer> $translationTransfers
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     * @param array<\Generated\Shared\Transfer\GlossaryKeyTransfer> $glossaryKeyTransfers
     *
     * @return array<string[]>
     */
    public function mapTranslationsByKeyNameAndLocaleName(
        array $translationTransfers,
        array $localeTransfers,
        array $glossaryKeyTransfers
    ): array;
}
