<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteResponseExpander;

use Generated\Shared\Transfer\QuoteResponseTransfer;

class QuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var array|\Spryker\Zed\SharedCart\Business\QuoteResponseExpander\QuoteResponseExpanderInterface[]
     */
    protected $quoteResponseExpanderList;

    /**
     * @param \Spryker\Zed\SharedCart\Business\QuoteResponseExpander\QuoteResponseExpanderInterface[] $quoteResponseExpanderList
     */
    public function __construct(array $quoteResponseExpanderList)
    {
        $this->quoteResponseExpanderList = $quoteResponseExpanderList;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        foreach ($this->quoteResponseExpanderList as $quoteResponseExpander) {
            $quoteResponseTransfer = $quoteResponseExpander->expand($quoteResponseTransfer);
        }

        return $quoteResponseTransfer;
    }
}
