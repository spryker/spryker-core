<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface;

/**
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class QuickOrderQuoteNameExpanderPlugin extends AbstractPlugin implements PersistentCartChangeExpanderPluginInterface
{
    public const PARAM_SUBMIT_BUTTON_CREATE_ORDER = 'createOrder';

    /**
     * Specification:
     * - Takes quote id form params and replace it in quote change request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function extend(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        if (isset($params[self::PARAM_SUBMIT_BUTTON_CREATE_ORDER])) {
            $quoteUpdateRequestAttributes = new QuoteUpdateRequestAttributesTransfer();
            if ($cartChangeTransfer->getQuoteUpdateRequestAttributes()) {
                $quoteUpdateRequestAttributes = $cartChangeTransfer->getQuoteUpdateRequestAttributes();
            }
            $quoteUpdateRequestAttributes->setName(
                sprintf($this->getFactory()->getMultiCartConfig()->getQuickOrderQuoteName(), date('Y-m-d H:i:s'))
            );
            $cartChangeTransfer->setIdQuote(0);
            $cartChangeTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributes);
        }

        return $cartChangeTransfer;
    }
}
