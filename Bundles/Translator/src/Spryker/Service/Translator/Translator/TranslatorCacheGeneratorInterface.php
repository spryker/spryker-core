<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\Translator;

interface TranslatorCacheGeneratorInterface
{
    /**
     * @param string $localeName
     *
     * @return void
     */
    public function generateCacheForLocale(string $localeName): void;
}
