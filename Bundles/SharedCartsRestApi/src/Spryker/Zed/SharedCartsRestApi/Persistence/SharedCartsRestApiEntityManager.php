<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Persistence;

use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiPersistenceFactory getFactory()
 */
class SharedCartsRestApiEntityManager extends AbstractEntityManager implements SharedCartsRestApiEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteCompanyUserTransfer $quoteCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCompanyUserTransfer
     */
    public function saveQuoteCompanyUser(QuoteCompanyUserTransfer $quoteCompanyUserTransfer): QuoteCompanyUserTransfer
    {
        $sharedCartRestApiMapper = $this->getFactory()->createSharedCartRestApiMapper();

        $quoteCompanyUserEntity = $sharedCartRestApiMapper->mapQuoteCompanyUserTransferToQuoteCompanyUserEntity(
            $quoteCompanyUserTransfer,
            new SpyQuoteCompanyUser()
        );
        $quoteCompanyUserEntity->save();

        return $sharedCartRestApiMapper->mapQuoteCompanyUserEntityToQuoteCompanyUserTransfer(
            $quoteCompanyUserEntity,
            new QuoteCompanyUserTransfer()
        );
    }
}
