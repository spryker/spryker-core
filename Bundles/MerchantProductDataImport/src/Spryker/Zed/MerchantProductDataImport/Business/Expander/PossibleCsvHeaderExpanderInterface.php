<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\Expander;

use Generated\Shared\Transfer\MerchantTransfer;

interface PossibleCsvHeaderExpanderInterface
{
    /**
     * @param array<string, list<string>> $possibleCsvHeaders
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, list<string>>
     */
    public function expand(array $possibleCsvHeaders, MerchantTransfer $merchantTransfer): array;
}
