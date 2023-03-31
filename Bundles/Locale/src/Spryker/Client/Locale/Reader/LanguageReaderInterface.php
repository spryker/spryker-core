<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Locale\Reader;

interface LanguageReaderInterface
{
    /**
     * @param string $localeCode
     *
     * @return string
     */
    public function getLanguageByLocaleCode(string $localeCode): string;

    /**
     * @param array<string, string> $localeList
     *
     * @return array<string>
     */
    public function getAllowedLanguagesByLocaleList(array $localeList): array;
}
