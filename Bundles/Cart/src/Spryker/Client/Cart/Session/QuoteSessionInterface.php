<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart\Session;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteSessionInterface
{

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return $this
     */
    public function setQuote(QuoteTransfer $quoteTransfer);

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param int $itemCount
     *
     * @return $this
     */
    public function setItemCount($itemCount);

    /**
     * @return void
     */
    public function clearQuote();

}
