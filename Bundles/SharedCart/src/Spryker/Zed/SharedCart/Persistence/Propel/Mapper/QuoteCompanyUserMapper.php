<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser;

class QuoteCompanyUserMapper implements QuoteCompanyUserMapperInterface
{
    /**
     * @param \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser $spyQuoteCompanyUser
     * @param \Generated\Shared\Transfer\QuoteCompanyUserTransfer $quoteCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCompanyUserTransfer
     */
    public function mapQuoteCompanyUserEntityToQuoteCompanyUserTransfer(
        SpyQuoteCompanyUser $spyQuoteCompanyUser,
        QuoteCompanyUserTransfer $quoteCompanyUserTransfer
    ): QuoteCompanyUserTransfer {
        $quoteCompanyUserTransfer->fromArray($spyQuoteCompanyUser->toArray(), true);
        $quoteCompanyUserTransfer->setQuote((new QuoteTransfer())->fromArray(
            $spyQuoteCompanyUser->getSpyQuote()->toArray(),
            true
        ));

        return $quoteCompanyUserTransfer->fromArray($spyQuoteCompanyUser->toArray(), true);
    }
}
