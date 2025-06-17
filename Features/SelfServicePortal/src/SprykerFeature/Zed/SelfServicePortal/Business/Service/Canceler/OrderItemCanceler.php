<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Canceler;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;

class OrderItemCanceler implements OrderItemCancelerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED = 'self_service_portal.service.validation.no_order_items_provided';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STATUS_CHANGE_FAILED = 'self_service_portal.service.validation.status_change_failed';

    /**
     * @var string
     */
    protected const EVENT_CANCEL = 'cancel';

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected OmsFacadeInterface $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct(OmsFacadeInterface $omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function cancelSalesOrderItemCollection(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $salesOrderItemCollectionRequestTransfer->requireItems();

        if (!$salesOrderItemCollectionRequestTransfer->getItems()->count()) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED);
        }

        $salesOrderItemIds = [];
        foreach ($salesOrderItemCollectionRequestTransfer->getItems() as $item) {
            $salesOrderItemIds[] = $item->getIdSalesOrderItem();
        }

        $result = $this->omsFacade->triggerEventForOrderItems(static::EVENT_CANCEL, $salesOrderItemIds);

        $salesOrderItemCollectionResponseTransfer = new SalesOrderItemCollectionResponseTransfer();

        if ($result === null) {
            $salesOrderItemCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_VALIDATION_STATUS_CHANGE_FAILED),
            );
        }

        $salesOrderItemCollectionResponseTransfer->setItems($salesOrderItemCollectionRequestTransfer->getItems());

        return $salesOrderItemCollectionResponseTransfer;
    }

    /**
     * @param string $errorMessage
     * @param array<string, mixed> $parameters
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    protected function createErrorResponse(string $errorMessage, array $parameters = []): SalesOrderItemCollectionResponseTransfer
    {
        $errorTransfer = (new ErrorTransfer())
            ->setMessage($errorMessage)
            ->setParameters($parameters);

        return (new SalesOrderItemCollectionResponseTransfer())
            ->addError($errorTransfer)
            ->setItems(new ArrayObject());
    }
}
