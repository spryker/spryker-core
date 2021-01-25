<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig;
use Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface;

class OrderCustomReferenceWriter implements OrderCustomReferenceWriterInterface
{
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_WAS_NOT_CHANGED = 'order_custom_reference.reference_not_saved';

    /**
     * @var \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface
     */
    protected $orderCustomReferenceEntityManager;

    /**
     * @var \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig
     */
    protected $orderCustomReferenceConfig;

    /**
     * @param \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface $orderCustomReferenceEntityManager
     * @param \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig $orderCustomReferenceConfig
     */
    public function __construct(
        OrderCustomReferenceEntityManagerInterface $orderCustomReferenceEntityManager,
        OrderCustomReferenceConfig $orderCustomReferenceConfig
    ) {
        $this->orderCustomReferenceEntityManager = $orderCustomReferenceEntityManager;
        $this->orderCustomReferenceConfig = $orderCustomReferenceConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function saveOrderCustomReferenceFromQuote(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): OrderCustomReferenceResponseTransfer
    {
        if ($quoteTransfer->getOrderCustomReference() && $saveOrderTransfer->getIdSalesOrder()) {
            return $this->orderCustomReferenceEntityManager
                ->saveOrderCustomReference(
                    $saveOrderTransfer->getIdSalesOrder(),
                    $quoteTransfer->getOrderCustomReference()
                );
        }

        return (new OrderCustomReferenceResponseTransfer())->setIsSuccessful(false);
    }

    /**
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function updateOrderCustomReference(
        string $orderCustomReference,
        OrderTransfer $orderTransfer
    ): OrderCustomReferenceResponseTransfer {
        $orderCustomReferenceResponseTransfer = (new OrderCustomReferenceResponseTransfer())
            ->setIsSuccessful(true);

        if (!$orderTransfer->getIdSalesOrder()) {
            return $orderCustomReferenceResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_WAS_NOT_CHANGED)
                );
        }

        if (!$this->isOrderCustomReferenceLengthValid($orderCustomReference)) {
            return $orderCustomReferenceResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH)
                );
        }

        $this->orderCustomReferenceEntityManager
            ->saveOrderCustomReference($orderTransfer->getIdSalesOrder(), $orderCustomReference);

        return $orderCustomReferenceResponseTransfer;
    }

    /**
     * @param string $orderCustomReference
     *
     * @return bool
     */
    protected function isOrderCustomReferenceLengthValid(string $orderCustomReference): bool
    {
        if (mb_strlen($orderCustomReference) > $this->orderCustomReferenceConfig->getOrderCustomReferenceMaxLength()) {
            return false;
        }

        return true;
    }
}
