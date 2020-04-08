<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';

    protected const GLOSSARY_KEY_INACTIVE_MERCHANT = 'merchant.message.inactive';
    protected const GLOSSARY_KEY_REMOVED_MERCHANT = 'merchant.message.removed';

    protected const GLOSSARY_PARAM_SKU = '%sku%';
    protected const GLOSSARY_PARAM_MERCHANT_NAME = '%merchant_name%';

    /**
     * {@inheritDoc}
     * - Checks if cart change transfer has items with inactive merchants.
     * - Returns unsuccessful response with error messages, if cart change transfer has items with inactive merchants.
     *
     * @api
     *
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
                    ->setParameters([static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku()]);

                continue;
            }

            $merchantTransfer = $merchantTransfers[$itemTransfer->getMerchantReference()];
            if (!$merchantTransfer->getIsActive()) {
                $messageTransfers[] = (new MessageTransfer())
                    ->setType(static::MESSAGE_TYPE_ERROR)
                    ->setValue(static::GLOSSARY_KEY_INACTIVE_MERCHANT)
                    ->setParameters([
                        static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku(),
                        static::GLOSSARY_PARAM_MERCHANT_NAME => $merchantTransfer->getName(),
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
        $merchantReferenes = [];
        $merchantTransfers = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }
            $merchantReferenes[] = $itemTransfer->getMerchantReference();
        }

        if (!$merchantReferenes) {
            return $merchantTransfers;
        }

        $merchantReferenes = array_unique($merchantReferenes);
        $merchantCollectionTransfer = $this->getFacade()->get(
            (new MerchantCriteriaTransfer())
                ->setMerchantReferences($merchantReferenes)
        );
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantTransfers[$merchantTransfer->getMerchantReference()] = $merchantTransfer;
        }

        return $merchantTransfers;
    }
}
