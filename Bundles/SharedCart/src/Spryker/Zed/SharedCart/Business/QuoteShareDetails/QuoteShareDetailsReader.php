<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteShareDetails;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class QuoteShareDetailsReader implements QuoteShareDetailsReaderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     */
    public function __construct(SharedCartRepositoryInterface $sharedCartRepository)
    {
        $this->sharedCartRepository = $sharedCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getShareDetailsByIdQuote(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer
    {
        $quoteTransfer->requireIdQuote();

        return $this->sharedCartRepository->findShareDetailsByQuoteId($quoteTransfer->getIdQuote());
    }
}
