<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade;

use Generated\Shared\Transfer\CurrencyTransfer;

interface SalesOrderThresholdDataImportToCurrencyFacadeInterface
{
    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    public function fromIsoCode(string $isoCode): ?CurrencyTransfer;
}
