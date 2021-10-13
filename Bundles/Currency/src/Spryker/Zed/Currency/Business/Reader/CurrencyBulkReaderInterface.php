<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Reader;

interface CurrencyBulkReaderInterface
{
    /**
     * @param array<string> $isoCodes
     *
     * @return array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    public function getCurrencyTransfersByIsoCodes(array $isoCodes): array;
}
