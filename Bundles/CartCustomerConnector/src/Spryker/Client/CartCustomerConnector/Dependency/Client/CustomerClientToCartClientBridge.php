<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCustomerConnector\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;

class CustomerClientToCartClientBridge implements CustomerClientToCartClientInterface
{

    /**
     * @var CartClientInterface
     */
    protected $cartClient;

    /**
     * @param CartClientInterface $cartClient
     */
    public function __construct($cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @return QuoteTransfer
     */
    public function getQuote()
    {
        return $this->cartClient->getQuote();
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function storeQuote(QuoteTransfer $quoteTransfer)
    {
        $this->cartClient->storeQuote($quoteTransfer);
    }

}
