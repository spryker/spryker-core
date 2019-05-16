<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Persistence\Mapper;

use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser;

class SharedCartsRestApiMapper implements SharedCartsRestApiMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteCompanyUserTransfer $quoteCompanyUserTransfer
     * @param \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser $quoteCompanyUserEntity
     *
     * @return \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser
     */
    public function mapQuoteCompanyUserTransferToQuoteCompanyUserEntity(
        QuoteCompanyUserTransfer $quoteCompanyUserTransfer,
        SpyQuoteCompanyUser $quoteCompanyUserEntity
    ): SpyQuoteCompanyUser {
        $quoteCompanyUserEntity->fromArray($quoteCompanyUserTransfer->modifiedToArray());
        $quoteCompanyUserEntity->setNew($quoteCompanyUserTransfer->getIdQuoteCompanyUser() === null);

        return $quoteCompanyUserEntity;
    }

    /**
     * @param \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser $quoteCompanyUserEntity
     * @param \Generated\Shared\Transfer\QuoteCompanyUserTransfer $quoteCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCompanyUserTransfer
     */
    public function mapQuoteCompanyUserEntityToQuoteCompanyUserTransfer(
        SpyQuoteCompanyUser $quoteCompanyUserEntity,
        QuoteCompanyUserTransfer $quoteCompanyUserTransfer
    ): QuoteCompanyUserTransfer {
        return $quoteCompanyUserTransfer->fromArray($quoteCompanyUserEntity->toArray(), true);
    }
}
