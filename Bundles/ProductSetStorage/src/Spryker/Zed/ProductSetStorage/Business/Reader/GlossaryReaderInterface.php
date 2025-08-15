<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business\Reader;

interface GlossaryReaderInterface
{
    /**
     * @param list<string> $glossaryKeys
     * @param string $localeName
     *
     * @return array<string, string|null>
     */
    public function getTranslationsIndexedByGlossaryKey(array $glossaryKeys, string $localeName): array;
}
