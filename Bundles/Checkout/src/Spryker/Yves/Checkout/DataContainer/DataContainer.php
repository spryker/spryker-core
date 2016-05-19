<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\DataContainer;

use Spryker\Client\Cart\CartClientInterface;
use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface;

class DataContainer implements DataContainerInterface
{

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    private $cartClient;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    private $quoteTransfer;

    /**
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     */
    public function __construct(CartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function get()
    {
        if (!$this->quoteTransfer) {
            $this->quoteTransfer = $this->cartClient->getQuote();
        }

        return $this->quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return void
     */
    public function set(AbstractTransfer $dataTransfer)
    {
        $this->cartClient->storeQuote($transfer);
    }

}
