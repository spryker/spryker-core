<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

interface StoresCurrencyReaderInterface
{
    /**
     * @param array $isoCodes
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer[]
     */
    public function getStoresCurrencyAttributes(array $isoCodes): array;
}
