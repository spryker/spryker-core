<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\StoresRestAttributesTransfer;

interface StoresResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer[] $countries
     * @param \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer[] $currencies
     *
     * @return \Generated\Shared\Transfer\StoresRestAttributesTransfer
     */
    public function mapStoreToStoresRestAttribute(array $countries, array $currencies): StoresRestAttributesTransfer;
}
