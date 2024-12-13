<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDiscountConnector\Business\Reader;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToMerchantFacadeInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToMerchantFacadeInterface
     */
    protected MerchantDiscountConnectorToMerchantFacadeInterface $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantDiscountConnectorToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @return array<string, string>
     */
    public function getMerchantNamesIndexedByMerchantReference(): array
    {
        $merchantCollectionTransfer = $this->merchantFacade->get(new MerchantCriteriaTransfer());
        $indexedMerchantNames = [];
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $indexedMerchantNames[$merchantTransfer->getMerchantReference()] = $merchantTransfer->getNameOrFail();
        }

        return $indexedMerchantNames;
    }
}
