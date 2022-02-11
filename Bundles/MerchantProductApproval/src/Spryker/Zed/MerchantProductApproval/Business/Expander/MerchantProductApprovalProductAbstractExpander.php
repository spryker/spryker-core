<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductApproval\Business\Expander;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\MerchantProductApproval\Dependency\Facade\MerchantProductApprovalToMerchantProductFacadeInterface;

class MerchantProductApprovalProductAbstractExpander implements MerchantProductApprovalProductAbstractExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductApproval\Dependency\Facade\MerchantProductApprovalToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @param \Spryker\Zed\MerchantProductApproval\Dependency\Facade\MerchantProductApprovalToMerchantProductFacadeInterface $merchantProductFacade
     */
    public function __construct(MerchantProductApprovalToMerchantProductFacadeInterface $merchantProductFacade)
    {
        $this->merchantProductFacade = $merchantProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expand(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        if ($productAbstractTransfer->getApprovalStatus() !== null) {
            return $productAbstractTransfer;
        }

        $idMerchant = $productAbstractTransfer->getIdMerchant();

        if (!$idMerchant) {
            return $productAbstractTransfer;
        }

        $merchantProductCriteriaTransfer = (new MerchantProductCriteriaTransfer())->setIdMerchant($idMerchant);

        $merchantTransfer = $this->merchantProductFacade->findMerchant($merchantProductCriteriaTransfer);

        if (!$merchantTransfer) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->setApprovalStatus($merchantTransfer->getDefaultProductAbstractApprovalStatus());

        return $productAbstractTransfer;
    }
}
