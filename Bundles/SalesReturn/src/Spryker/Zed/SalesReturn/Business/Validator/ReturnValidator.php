<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class ReturnValidator implements ReturnValidatorInterface
{
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR = 'return.create_return.validation.items_error';
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_CURRENCY_ERROR = 'return.create_return.validation.items_currency_error';
    protected const GLOSSARY_KEY_CREATE_RETURN_RETURNABLE_ITEM_ERROR = 'return.create_return.validation.returnable_items_error';
    protected const GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR = 'return.create_return.validation.store_error';

    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\SalesReturn\SalesReturnConfig
     */
    protected $salesReturnConfig;

    /**
     * @var \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnRequestValidatorPluginInterface[]
     */
    protected $returnRequestValidatorPlugins;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\SalesReturn\SalesReturnConfig $salesReturnConfig
     * @param \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnRequestValidatorPluginInterface[] $returnRequestValidatorPlugins
     */
    public function __construct(
        SalesReturnToStoreFacadeInterface $storeFacade,
        SalesReturnConfig $salesReturnConfig,
        array $returnRequestValidatorPlugins
    ) {
        $this->storeFacade = $storeFacade;
        $this->salesReturnConfig = $salesReturnConfig;
        $this->returnRequestValidatorPlugins = $returnRequestValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validateReturnRequest(
        ReturnCreateRequestTransfer $returnCreateRequestTransfer,
        ArrayObject $itemTransfers
    ): ReturnResponseTransfer {
        if ($returnCreateRequestTransfer->getReturnItems()->count() !== $itemTransfers->count()) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR);
        }

        if (!$this->checkOrderItemCurrencies($itemTransfers)) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_ITEM_CURRENCY_ERROR);
        }

        if (!$this->isOrderItemsReturnable($itemTransfers)) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_RETURNABLE_ITEM_ERROR);
        }

        if (!$this->storeFacade->findStoreByName($returnCreateRequestTransfer->getStore())) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR);
        }

        $returnResponseTransfer = $this->validateReturnRequest($returnCreateRequestTransfer, $itemTransfers);
        if (!$returnResponseTransfer->getIsSuccessful()) {
            return $returnResponseTransfer;
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    public function isOrderItemsReturnable(ArrayObject $itemTransfers): bool
    {
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getIsReturnable()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    protected function checkOrderItemCurrencies(ArrayObject $itemTransfers): bool
    {
        $currencyIsoCode = $itemTransfers->getIterator()->current()->getCurrencyIsoCode();

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getCurrencyIsoCode() !== $currencyIsoCode) {
                return false;
            }
        }

        return true;
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

    /**
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function executeValidatorPlugins(
        ReturnCreateRequestTransfer $returnCreateRequestTransfer,
        ArrayObject $itemTransfers
    ): ReturnResponseTransfer {
        foreach ($this->returnRequestValidatorPlugins as $requestValidatorPlugin) {
            $returnResponseTransfer = $requestValidatorPlugin->validate($returnCreateRequestTransfer, $itemTransfers);
            if (!$returnResponseTransfer->getIsSuccessful()) {
                return $returnResponseTransfer;
            }
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true);
    }
}
