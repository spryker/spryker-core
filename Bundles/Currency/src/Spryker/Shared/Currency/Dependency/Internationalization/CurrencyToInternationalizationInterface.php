<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Dependency\Internationalization;

interface CurrencyToInternationalizationInterface
{
    /**
     * @param string $isoCode
     *
     * @return string
     */
    public function getSymbolByIsoCode(string $isoCode): string;

    /**
     * @param string $isoCode
     *
     * @return string
     */
    public function getNameByIsoCode(string $isoCode): string;

    /**
     * @param string $isoCode
     *
     * @return int|null
     */
    public function getFractionDigits(string $isoCode): ?int;
}
