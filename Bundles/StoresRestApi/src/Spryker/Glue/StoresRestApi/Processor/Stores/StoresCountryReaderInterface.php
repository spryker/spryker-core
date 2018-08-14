<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Generated\Shared\Transfer\StoreCountryRestAttributesTransfer;

interface StoresCountryReaderInterface
{
    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer
     */
    public function getStoresCountryAttributes(string $iso2Code): StoreCountryRestAttributesTransfer;
}
