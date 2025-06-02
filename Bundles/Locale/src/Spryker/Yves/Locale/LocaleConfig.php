<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Locale;

use Spryker\Shared\Locale\LocaleConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class LocaleConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const STORE_LOCALE_INDEX = 1;

    /**
     * Specification:
     * - Returns the index of the locale segment in the URL path.
     * - The index refers to the position of the locale code in array resulted by separating URL by `/`.
     * - Example: For `/DE/en/product/123`, if the index is 1, the locale would be 'en'.
     *
     * @api
     *
     * @return int
     */
    public function getLocaleCodeIndex(): int
    {
        return static::STORE_LOCALE_INDEX;
    }

    /**
     * Specification:
     * - Returns true if the store routing is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isStoreRoutingEnabled(): bool
    {
        return $this->get(LocaleConstants::IS_STORE_ROUTING_ENABLED, false);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getConsoleDefaultLocale(): string
    {
        return 'en_US';
    }
}
