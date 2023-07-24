<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Reader;

interface StoresCountryReaderInterface
{
    /**
     * @param array $iso2Codes
     *
     * @return array<\Generated\Shared\Transfer\ApiStoreCountryAttributesTransfer>
     */
    public function getStoresCountryAttributes(array $iso2Codes): array;
}
