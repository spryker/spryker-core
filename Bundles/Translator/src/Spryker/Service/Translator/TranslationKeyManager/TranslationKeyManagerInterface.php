<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslationKeyManager;

interface TranslationKeyManagerInterface
{
    /**
     * @param string $message
     * @param string $localeName
     *
     * @return bool
     */
    public function hasTranslation(string $message, string $localeName): bool;
}
