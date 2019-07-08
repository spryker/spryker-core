<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface GlossaryRepositoryInterface
{
    /**
     * @param string $keyName
     * @param array $localeIsoCodes
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer[]
     */
    public function getTranslationsByGlossaryKeyAndLocaleIsoCodes(string $keyName, array $localeIsoCodes): array;

    /**
     * @param array $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[]
     */
    public function findGlossaryTranslationEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryKeyEntityTransfer[]
     */
    public function findFilteredGlossaryKeyEntityTransfers(FilterTransfer $filterTransfer): array;
}
