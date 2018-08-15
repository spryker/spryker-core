<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

interface StoresCountryReaderInterface
{
    /**
     * @param array $iso2Codes
     *
     * @return \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer[]
     */
    public function getStoresCountryAttributes(array $iso2Codes): array;
}
