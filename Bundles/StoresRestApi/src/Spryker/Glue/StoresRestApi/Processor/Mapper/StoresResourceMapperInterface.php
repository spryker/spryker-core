<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\StoresRestAttributesTransfer;
use Spryker\Shared\Kernel\Store;

interface StoresResourceMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer[] $countries
     * @param \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer[] $currencies
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapStoreToStoresRestAttribute(Store $store, array $countries, array $currencies): StoresRestAttributesTransfer;
}
