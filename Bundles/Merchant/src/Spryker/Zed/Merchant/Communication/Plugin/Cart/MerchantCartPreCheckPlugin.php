<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\Cart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\Merchant\Communication\MerchantCommunicationFactory getFactory()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';

    protected const GLOSSARY_KEY_REMOVED_MERCHANT = 'merchant.message.removed';

    protected const GLOSSARY_PARAM_SKU = '%sku%';

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

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }

            $merchantTransfer = $this->getFacade()->findOne(
                (new MerchantCriteriaFilterTransfer())
                    ->setMerchantReference($itemTransfer->getMerchantReference())
                    ->setIsActive(true)
                    ->setStore(
                        $this->getFactory()
                            ->getStoreFacade()
                            ->getCurrentStore()
                            ->getName()
                    )
            );

            if (!$merchantTransfer) {
                $messageTransfers[] = (new MessageTransfer())
                    ->setType(static::MESSAGE_TYPE_ERROR)
                    ->setValue(static::GLOSSARY_KEY_REMOVED_MERCHANT)
                    ->setParameters([static::GLOSSARY_PARAM_SKU => $itemTransfer->getSku()]);
            }
        }

        return (new CartPreCheckResponseTransfer())
            ->setMessages(new ArrayObject($messageTransfers))
            ->setIsSuccess(!$messageTransfers);
    }
}
