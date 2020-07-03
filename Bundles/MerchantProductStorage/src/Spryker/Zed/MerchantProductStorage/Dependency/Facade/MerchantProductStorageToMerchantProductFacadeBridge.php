<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Dependency\Facade;

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
     * @param int[] $idProductAbstractMerchants
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer[]
     */
    public function findMerchantProducts(array $idProductAbstractMerchants): array
    {
        return $this->merchantProductFacade->findMerchantProducts($idProductAbstractMerchants);
    }
}
