<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FactFinder\Business\FactFinderBusinessFactory getFactory()
 */
class FactFinderFacade extends AbstractFacade implements FactFinderFacadeInterface
{

    /**
     * Specification:
     * - search request
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FFSearchResponseTransfer
     */
    public function search(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createSearchRequest()
            ->request($quoteTransfer);
    }

}
