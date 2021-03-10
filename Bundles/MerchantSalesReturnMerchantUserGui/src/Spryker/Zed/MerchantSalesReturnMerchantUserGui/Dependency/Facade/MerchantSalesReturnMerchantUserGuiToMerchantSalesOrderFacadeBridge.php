<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;

class MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeBridge implements MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface
     */
    protected $merchantSalesOrderFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     */
    public function __construct($merchantSalesOrderFacade)
    {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
    }

//    /**
//     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantCriteriaTransfer
//     *
//     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
//     */
//    public function findMerchantOrder(MerchantOrderCriteriaTransfer $merchantCriteriaTransfer): ?MerchantOrderTransfer
//    {
//        $merchantOrderTransfer = $this->merchantSalesOrderFacade->findMerchantOrder($merchantCriteriaTransfer);
//
//        return $merchantOrderTransfer;
//    }

//    /**
//     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
//     *
//     * @return int
//     */
//    public function getMerchantOrdersCount(MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer): int
//    {
//        return $this->merchantSalesOrderFacade->getMerchantOrdersCount($merchantOrderCriteriaTransfer);
//    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    public function getMerchantOrderItemCollection(MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer): MerchantOrderItemCollectionTransfer
    {
        return $this->merchantSalesOrderFacade->getMerchantOrderItemCollection($merchantOrderItemCriteriaTransfer);
    }
}
