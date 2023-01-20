<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Dependency\Facade;

use Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

class MerchantProductStorageToMerchantProductFacadeBridge implements MerchantProductStorageToMerchantProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantProduct\Business\MerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @param \Spryker\Zed\MerchantProduct\Business\MerchantProductFacadeInterface $merchantProductFacade
     */
    public function __construct($merchantProductFacade)
    {
        $this->merchantProductFacade = $merchantProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchant(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): ?MerchantTransfer
    {
        return $this->merchantProductFacade->findMerchant($merchantProductCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductCollectionTransfer
     */
    public function get(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): MerchantProductCollectionTransfer
    {
        return $this->merchantProductFacade->get($merchantProductCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer $merchantProductAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductAbstractCollectionTransfer
     */
    public function getMerchantProductAbstractCollection(
        MerchantProductAbstractCriteriaTransfer $merchantProductAbstractCriteriaTransfer
    ): MerchantProductAbstractCollectionTransfer {
        return $this->merchantProductFacade->getMerchantProductAbstractCollection($merchantProductAbstractCriteriaTransfer);
    }
}
