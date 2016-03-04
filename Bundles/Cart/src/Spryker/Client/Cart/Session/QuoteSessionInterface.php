<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
