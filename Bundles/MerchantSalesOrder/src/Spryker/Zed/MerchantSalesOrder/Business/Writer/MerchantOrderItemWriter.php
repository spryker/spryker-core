<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Writer;

use Generated\Shared\Transfer\MerchantOrderItemResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface;

class MerchantOrderItemWriter implements MerchantOrderItemWriterInterface
{
    protected const MESSAGE_MERCHANT_ORDER_ITEM_NOT_FOUND = 'Merchant order item not found.';

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected $merchantSalesOrderEntityManager;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface
     */
    protected $merchantSalesOrderRepository;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository
     */
    public function __construct(
        MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager,
        MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository
    ) {
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
        $this->merchantSalesOrderRepository = $merchantSalesOrderRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function update(MerchantOrderItemTransfer $merchantOrderItemTransfer): MerchantOrderItemResponseTransfer
    {
        $merchantOrderItemTransfer->requireIdMerchantOrderItem();
        $merchantOrderItemResponseTransfer = (new MerchantOrderItemResponseTransfer())->setIsSuccessful(true);

        if (!$this->merchantSalesOrderRepository->existsMerchantOrderItem($merchantOrderItemTransfer->getIdMerchantOrderItem())) {
            return $this->addErrorMessage($merchantOrderItemResponseTransfer, static::MESSAGE_MERCHANT_ORDER_ITEM_NOT_FOUND);
        }

        $merchantOrderItemTransfer = $this->merchantSalesOrderEntityManager->updateMerchantOrderItem($merchantOrderItemTransfer);

        $merchantOrderItemResponseTransfer->setMerchantOrderItem($merchantOrderItemTransfer);

        return $merchantOrderItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer $merchantOrderItemResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    protected function addErrorMessage(MerchantOrderItemResponseTransfer $merchantOrderItemResponseTransfer, string $message): MerchantOrderItemResponseTransfer
    {
        return $merchantOrderItemResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage((new MessageTransfer())->setMessage($message));
    }
}
