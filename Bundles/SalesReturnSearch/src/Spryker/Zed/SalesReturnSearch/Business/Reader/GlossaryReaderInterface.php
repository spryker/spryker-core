<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business\Reader;

interface GlossaryReaderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ReturnReasonTransfer> $returnReasonTransfers
     *
     * @return array
     */
    public function getReturnReasonTranslations(array $returnReasonTransfers): array;

    /**
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollection(): array;
}
