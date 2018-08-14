<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer;

interface StoresCurrencyReaderInterface
{
    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer
     */
    public function getStoresCurrencyAttributes(string $isoCode): StoreCurrencyRestAttributesTransfer;
}
