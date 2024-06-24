<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;

class MerchantCommissionGuiToMerchantCommissionFacadeBridge implements MerchantCommissionGuiToMerchantCommissionFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface
     */
    protected $merchantCommissionFacade;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface $merchantCommissionFacade
     */
    public function __construct($merchantCommissionFacade)
    {
        $this->merchantCommissionFacade = $merchantCommissionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function getMerchantCommissionCollection(
        MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
    ): MerchantCommissionCollectionTransfer {
        return $this->merchantCommissionFacade->getMerchantCommissionCollection($merchantCommissionCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function importMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer {
        return $this->merchantCommissionFacade->importMerchantCommissionCollection($merchantCommissionCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
     *
     * @return int
     */
    public function transformMerchantCommissionAmountForPersistence(
        MerchantCommissionAmountTransformerRequestTransfer $merchantCommissionAmountTransformerRequestTransfer
    ): int {
        return $this->merchantCommissionFacade->transformMerchantCommissionAmountForPersistence(
            $merchantCommissionAmountTransformerRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountFormatRequestTransfer $merchantCommissionAmountFormatRequestTransfer
     *
     * @return string
     */
    public function formatMerchantCommissionAmount(
        MerchantCommissionAmountFormatRequestTransfer $merchantCommissionAmountFormatRequestTransfer
    ): string {
        return $this->merchantCommissionFacade->formatMerchantCommissionAmount(
            $merchantCommissionAmountFormatRequestTransfer,
        );
    }
}
