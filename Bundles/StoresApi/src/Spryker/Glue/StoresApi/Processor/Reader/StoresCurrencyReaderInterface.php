<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Reader;

interface StoresCurrencyReaderInterface
{
    /**
     * @param array $isoCodes
     *
     * @return array<\Generated\Shared\Transfer\ApiStoreCurrencyAttributesTransfer>
     */
    public function getStoresCurrencyAttributes(array $isoCodes): array;
}
