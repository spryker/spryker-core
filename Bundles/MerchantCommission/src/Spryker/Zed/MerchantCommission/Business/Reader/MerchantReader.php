<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToMerchantFacadeInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToMerchantFacadeInterface
     */
    protected MerchantCommissionToMerchantFacadeInterface $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantCommissionToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param list<int> $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchantCollectionByMerchantIds(array $merchantIds): MerchantCollectionTransfer
    {
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())->setMerchantIds($merchantIds);

        return $this->merchantFacade->get($merchantCriteriaTransfer);
    }

    /**
     * @param list<string> $merchantReferences
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchantCollectionByMerchantReferences(array $merchantReferences): MerchantCollectionTransfer
    {
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())->setMerchantReferences($merchantReferences);

        return $this->merchantFacade->get($merchantCriteriaTransfer);
    }
}
