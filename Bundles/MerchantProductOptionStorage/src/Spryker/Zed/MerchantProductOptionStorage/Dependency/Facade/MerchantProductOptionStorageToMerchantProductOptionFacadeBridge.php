<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade;

use Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;

class MerchantProductOptionStorageToMerchantProductOptionFacadeBridge implements MerchantProductOptionStorageToMerchantProductOptionFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOption\Business\MerchantProductOptionFacadeInterface
     */
    protected $merchantProductOptionFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOption\Business\MerchantProductOptionFacadeInterface $merchantProductOptionFacade
     */
    public function __construct($merchantProductOptionFacade)
    {
        $this->merchantProductOptionFacade = $merchantProductOptionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer
     */
    public function getGroups(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): MerchantProductOptionGroupCollectionTransfer {
        return $this->merchantProductOptionFacade->getGroups($merchantProductOptionGroupCriteriaTransfer);
    }
}
