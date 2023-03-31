<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Locale\Reader;

use LogicException;

class LanguageReader implements LanguageReaderInterface
{
    /**
     * @param string $localeCode
     *
     * @throws \LogicException
     *
     * @return string
     */
    public function getLanguageByLocaleCode(string $localeCode): string
    {
        $delimiterPosition = strpos($localeCode, '_');

        if ($delimiterPosition === false) {
            throw new LogicException('Locale should contain symbol `_`.');
        }

        return substr($localeCode, 0, $delimiterPosition);
    }

    /**
     * @param array<string, string> $localeList
     *
     * @return array<string>
     */
    public function getAllowedLanguagesByLocaleList(array $localeList): array
    {
        return array_keys($localeList);
    }
}
