<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\Cart\Validator;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;

class MerchantCartValidator implements MerchantCartValidatorInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REMOVED_MERCHANT = 'merchant.message.removed';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INACTIVE_MERCHANT = 'merchant.message.inactive';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_MERCHANT_REFERENCE = '%merchant_reference%';

    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * Format:
     * [
     *     $storeName => ['merchantReference' => MerchantTransfer],
     *     ...
     * ]
     *
     * @var array<string, array<string, \Generated\Shared\Transfer\MerchantTransfer>>
     */
    protected static $merchantTransfersCache;

    /**
     * @param \Spryker\Zed\Merchant\Business\MerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $messageTransfers = [];
        $merchantTransfers = $this->getMerchantTransfersGroupedByMerchantReference($cartChangeTransfer);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }

            if (!isset($merchantTransfers[$itemTransfer->getMerchantReference()])) {
                $messageTransfers[] = (new MessageTransfer())
                    ->setType(static::MESSAGE_TYPE_ERROR)
                    ->setValue(static::GLOSSARY_KEY_REMOVED_MERCHANT)
                    ->setParameters([static::GLOSSARY_PARAM_MERCHANT_REFERENCE => $itemTransfer->getMerchantReference()]);

                continue;
            }

            if (!$merchantTransfers[$itemTransfer->getMerchantReference()]->getIsActive()) {
                $messageTransfers[] = (new MessageTransfer())
                    ->setType(static::MESSAGE_TYPE_ERROR)
                    ->setValue(static::GLOSSARY_KEY_INACTIVE_MERCHANT)
                    ->setParameters([
                        static::GLOSSARY_PARAM_MERCHANT_REFERENCE => $itemTransfer->getMerchantReference(),
                    ]);
            }
        }

        return (new CartPreCheckResponseTransfer())
            ->setMessages(new ArrayObject($messageTransfers))
            ->setIsSuccess(!$messageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantTransfer>
     */
    protected function getMerchantTransfersGroupedByMerchantReference(CartChangeTransfer $cartChangeTransfer)
    {
        $merchantReferences = [];
        $merchantTransfers = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }
            $merchantReferences[] = $itemTransfer->getMerchantReference();
        }

        if (!$merchantReferences) {
            return $merchantTransfers;
        }
        /** @var array<string> $merchantReferences */
        $merchantReferences = array_unique($merchantReferences);

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $cartChangeTransfer->getQuote();

        return $this->getMerchants($merchantReferences, $quoteTransfer->getStoreOrFail());
    }

    /**
     * @param array<string> $merchantReferences
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantTransfer>
     */
    protected function getMerchants(array $merchantReferences, StoreTransfer $storeTransfer): array
    {
        $result = [];
        $missedReferences = [];
        $storeName = $storeTransfer->getNameOrFail();

        foreach ($merchantReferences as $merchantReference) {
            if (isset(static::$merchantTransfersCache[$storeName][$merchantReference])) {
                $result[$merchantReference] = static::$merchantTransfersCache[$storeName][$merchantReference];

                continue;
            }

            $missedReferences[] = $merchantReference;
        }

        if ($missedReferences === []) {
            return $result;
        }

        $merchantCollectionTransfer = $this->merchantFacade->get(
            (new MerchantCriteriaTransfer())
                ->setMerchantReferences($missedReferences)
                ->setStore($storeTransfer),
        );

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantReference = $merchantTransfer->getMerchantReferenceOrFail();

            foreach ($merchantTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
                static::$merchantTransfersCache[$storeTransfer->getNameOrFail()][$merchantReference] = $merchantTransfer;
            }

            $result[$merchantReference] = $merchantTransfer;
        }

        return $result;
    }
}
