<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationResponseTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherBusinessFactory getFactory()
 */
class MerchantSwitcherFacade extends AbstractFacade implements MerchantSwitcherFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuote(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantSwitcher()
            ->switchMerchantInQuote($merchantSwitchRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuoteItems(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantSwitcher()
            ->switchMerchantInQuoteItems($merchantSwitchRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer $singleMerchantQuoteValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SingleMerchantQuoteValidationResponseTransfer
     */
    public function validateMerchantInQuoteItems(
        SingleMerchantQuoteValidationRequestTransfer $singleMerchantQuoteValidationRequestTransfer
    ): SingleMerchantQuoteValidationResponseTransfer {
        return $this->getFactory()
            ->createMerchantInQuoteValidator()
            ->validateMerchantInQuoteItems($singleMerchantQuoteValidationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInWishlistItems(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        return $this->getFactory()
            ->createWishlistMerchantSwitcher()
            ->switchMerchantInWishlistItems($merchantSwitchRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateWishlistItems(WishlistTransfer $wishlistTransfer): ValidationResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantWishlistValidator()
            ->validateItems($wishlistTransfer);
    }
}
