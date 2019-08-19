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
    /**
     * Specification:
     * - Defines paths to Project level translations. Glob pattern can be used.
     */
    public const TRANSLATION_ZED_FILE_PATH_PATTERNS = 'TRANSLATOR:TRANSLATION_ZED_FILE_PATH_PATTERNS';

    /**
     * Specification:
     * - Absolute path to the translation cache directory. E.g. /var/www/data/DE/cache/Zed/translation.
     */
    public const TRANSLATION_ZED_CACHE_DIRECTORY = 'TRANSLATOR:TRANSLATION_ZED_CACHE_DIRECTORY';

    /**
     * Specification:
     * - Fallback locales that will be used if there is no translation for selected locale.
     *
     * @api
     */
    public const TRANSLATION_ZED_FALLBACK_LOCALES = 'TRANSLATOR:TRANSLATION_ZED_FALLBACK_LOCALES';

    /**
     * Specification:
     * - Enables default symfony translator debug.
     *
     * @api
     */
    public const ZED_TRANSLATOR_DEBUG_ENABLED = 'ZED_TRANSLATOR_DEBUG_ENABLED';
}
