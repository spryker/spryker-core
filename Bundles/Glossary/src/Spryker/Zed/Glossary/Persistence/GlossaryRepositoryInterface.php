<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence;

interface GlossaryRepositoryInterface
{
    /**
     * @param string $keyName
     * @param array $localeIsoCodes
     *
     * @return array
     */
    public function getTranslationsByGlossaryKeyAndLocaleIsoCodes(string $keyName, array $localeIsoCodes): array;
}
