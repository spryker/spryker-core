<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;

class DataImportMerchantToMerchantFacadeBridge implements DataImportMerchantToMerchantFacadeInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\Merchant\Business\MerchantFacadeInterface $merchantFacade
     */
    public function __construct($merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): MerchantCollectionTransfer
    {
        return $this->merchantFacade->get($merchantCriteriaTransfer);
    }
}
