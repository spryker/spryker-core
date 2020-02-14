<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\MerchantReferenceChecker;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMessengerFacadeInterface;

class MerchantReferenceChecker implements MerchantReferenceCheckerInterface
{
    protected const GLOSSARY_KEY_PRODUCT_IS_NOT_AVAILABLE = 'merchant_switcher.message.product_is_not_available';
    protected const GLOSSARY_PARAMETER_NAME = '%product_name%';

    /**
     * @var \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        MerchantSwitcherToMessengerFacadeInterface $messengerFacade
    ) {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();

        $merchantReference = $cartChangeTransfer->getQuote()->getMerchantReference();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getMerchantReference() !== $merchantReference) {
                $this->messengerFacade->addErrorMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_PRODUCT_IS_NOT_AVAILABLE)
                        ->setParameters([
                            static::GLOSSARY_PARAMETER_NAME => $itemTransfer->getName(),
                        ])
                );
                $cartPreCheckResponseTransfer->setIsSuccess(false);
            }
        }

        return $cartPreCheckResponseTransfer;
    }
}
