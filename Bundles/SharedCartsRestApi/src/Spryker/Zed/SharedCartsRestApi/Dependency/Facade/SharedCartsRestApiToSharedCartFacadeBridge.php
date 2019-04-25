<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

class SharedCartsRestApiToSharedCartFacadeBridge implements SharedCartsRestApiToSharedCartFacadeInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface $sharedCartFacade
     */
    public function __construct($sharedCartFacade)
    {
        $this->sharedCartFacade = $sharedCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailsByIdQuote(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer
    {
        return $this->sharedCartFacade->getShareDetailsByIdQuote($quoteTransfer);
    }
}
