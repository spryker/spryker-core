<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface;

class QuoteNameResolver implements QuoteNameResolverInterface
{
    /**
     * @var \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface
     */
    protected $multiCartRepository;

    /**
     * @param \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface $multiCartRepository
     */
    public function __construct(MultiCartRepositoryInterface $multiCartRepository)
    {
        $this->multiCartRepository = $multiCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function resolveCustomerQuoteName(QuoteTransfer $quoteTransfer): string
    {
        if ($this->multiCartRepository->checkQuoteNameAvailability($quoteTransfer)) {
            return $quoteTransfer->getName();
        }

        $quoteNameCollection = $this->multiCartRepository->findSimilarCustomerQuoteNames($quoteTransfer);
        if ($quoteNameCollection) {
            return $quoteTransfer->getName() . ' ' . $this->findBiggestQuoteSuffix($quoteNameCollection);
        }

        return $quoteTransfer->getName();
    }

    /**
     * @param string[] $quoteNameCollection
     *
     * @return int
     */
    protected function findBiggestQuoteSuffix(array $quoteNameCollection): int
    {
        $lastQuoteSuffix = 1;
        foreach ($quoteNameCollection as $quoteName) {
            preg_match_all('/^.+ (\d+)$/', $quoteName, $matches, PREG_SET_ORDER);
            if (isset($matches[0][1]) && $lastQuoteSuffix <= (int)$matches[0][1]) {
                $lastQuoteSuffix = (int)$matches[0][1] + 1;
            }
        }

        return $lastQuoteSuffix;
    }
}
