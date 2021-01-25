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
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;

class MerchantCartValidator implements MerchantCartValidatorInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';
    protected const GLOSSARY_KEY_REMOVED_MERCHANT = 'merchant.message.removed';
    protected const GLOSSARY_KEY_INACTIVE_MERCHANT = 'merchant.message.inactive';
    protected const GLOSSARY_PARAM_MERCHANT_REFERENCE = '%merchant_reference%';

    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    protected $merchantFacade;

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
     * @return array
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

        $merchantReferences = array_unique($merchantReferences);
        $merchantCollectionTransfer = $this->merchantFacade->get(
            (new MerchantCriteriaTransfer())
                ->setMerchantReferences($merchantReferences)
                ->setStore($cartChangeTransfer->getQuote()->getStore())
        );
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantTransfers[$merchantTransfer->getMerchantReference()] = $merchantTransfer;
        }

        return $merchantTransfers;
    }
}
