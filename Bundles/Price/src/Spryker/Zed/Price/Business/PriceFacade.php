<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Price\Business\PriceBusinessFactory getFactory()
 */
class PriceFacade extends AbstractFacade implements PriceFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getPriceModes()
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getPriceModes();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPriceMode()
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getDefaultPriceMode();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getNetPriceModeIdentifier();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return $this->getFactory()
           ->getModuleConfig()
           ->getGrossPriceModeIdentifier();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validatePriceModeInQuote(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        return $this->getFactory()->createQuoteValidator()->validate($quoteTransfer);
    }
}
