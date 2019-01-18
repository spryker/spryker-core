<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Translator;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface TranslatorConstants
{
    public const TRANSLATION_FILE_PATH_PATTERNS = 'TRANSLATOR:TRANSLATION_FILE_PATH_PATTERN';
    public const TRANSLATION_CACHE_DIRECTORY = 'TRANSLATOR:TRANSLATION_CACHE_DIRECTORY';

    /**
     * Specification:
     * - Fallback locales that will be used if there is no translation for selected locale.
     *
     * @api
     */
    public const TRANSLATION_FALLBACK_LOCALES = 'TRANSLATOR:FALLBACK_LOCALES';
}
