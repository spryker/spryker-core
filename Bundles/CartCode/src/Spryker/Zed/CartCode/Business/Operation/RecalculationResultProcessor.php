<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode\Business\Operation;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;

class RecalculationResultProcessor implements RecalculationResultProcessorInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var \Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    protected $cartCodePlugins;

    /**
     * @param \Spryker\Zed\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[] $cartCodePlugins
     */
    public function __construct(array $cartCodePlugins = [])
    {
        $this->cartCodePlugins = $cartCodePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function processRecalculationResults(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteTransfer = $cartCodeRequestTransfer->getQuote();
        $cartCodeResponseTransfer = (new CartCodeResponseTransfer())->setQuote($quoteTransfer);

        $isSuccess = true;
        $cartCode = $cartCodeRequestTransfer->getCartCode();
        foreach ($this->cartCodePlugins as $cartCodePlugin) {
            $messageTransfer = $cartCodePlugin->findOperationResponseMessage($quoteTransfer, $cartCode);
            if (!$messageTransfer) {
                continue;
            }

            $cartCodeResponseTransfer->addMessage($messageTransfer);
            $isSuccess = $isSuccess && $messageTransfer->getType() !== static::MESSAGE_TYPE_ERROR;
        }

        return $cartCodeResponseTransfer->setIsSuccessful($isSuccess);
    }
}
