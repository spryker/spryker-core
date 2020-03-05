<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\SalesReturn\Business\Checker\OrderItemCheckerInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface;

class ReturnValidator implements ReturnValidatorInterface
{
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR = 'return.create_return.validation.items_error';
    protected const GLOSSARY_KEY_CREATE_RETURN_RETURNABLE_ITEM_ERROR = 'return.create_return.validation.returnable_items_error';
    protected const GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR = 'return.create_return.validation.store_error';

    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\SalesReturn\Business\Checker\OrderItemCheckerInterface
     */
    protected $orderItemChecker;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\SalesReturn\Business\Checker\OrderItemCheckerInterface $orderItemChecker
     */
    public function __construct(
        SalesReturnToStoreFacadeInterface $storeFacade,
        OrderItemCheckerInterface $orderItemChecker
    ) {
        $this->storeFacade = $storeFacade;
        $this->orderItemChecker = $orderItemChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validateReturnRequest(
        CreateReturnRequestTransfer $createReturnRequestTransfer,
        ArrayObject $itemTransfers
    ): ReturnResponseTransfer {
        if (!$this->isOrderItemsRelatedToCustomer($createReturnRequestTransfer, $itemTransfers)) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR);
        }

        if (!$this->orderItemChecker->isOrderItemsInReturnableStates($itemTransfers)) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_RETURNABLE_ITEM_ERROR);
        }

        if (!$this->storeFacade->findStoreByName($createReturnRequestTransfer->getStore())) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR);
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    protected function isOrderItemsRelatedToCustomer(
        CreateReturnRequestTransfer $createReturnRequestTransfer,
        ArrayObject $itemTransfers
    ): bool {
        return $itemTransfers->count() === $createReturnRequestTransfer->getReturnItems()->count();
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function createErrorReturnResponse(string $message): ReturnResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
