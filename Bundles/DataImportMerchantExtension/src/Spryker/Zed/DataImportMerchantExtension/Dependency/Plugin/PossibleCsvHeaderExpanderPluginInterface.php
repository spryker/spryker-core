<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantTransfer;

/**
 * Use this plugin interface to expand possible csv headers.
 */
interface PossibleCsvHeaderExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands possible CSV headers.
     *
     * @api
     *
     * @param array<string, list<string>> $possibleCsvHeaders
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, list<string>>
     */
    public function expand(array $possibleCsvHeaders, MerchantTransfer $merchantTransfer): array;
}
