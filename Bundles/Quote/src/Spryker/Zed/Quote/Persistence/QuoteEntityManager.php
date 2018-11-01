<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use DateTime;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuotePersistenceFactory getFactory()
 */
class QuoteEntityManager extends AbstractEntityManager implements QuoteEntityManagerInterface
{
    protected const BATCH_SIZE_LIMIT = 200;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveQuote(QuoteTransfer $quoteTransfer)
    {
        $quoteMapper = $this->getFactory()->createQuoteMapper();
        $quoteEntity = $this->getFactory()
            ->createQuoteQuery()
            ->filterByIdQuote($quoteTransfer->getIdQuote())
            ->findOneOrCreate();
        $quoteEntity = $quoteMapper->mapTransferToEntity($quoteTransfer, $quoteEntity);
        $quoteEntity->save();
        $quoteTransfer->setIdQuote($quoteEntity->getIdQuote());

        return $quoteTransfer;
    }

    /**
     * @param int $idQuote
     *
     * @return void
     */
    public function deleteQuoteById($idQuote)
    {
        $this->getFactory()
            ->createQuoteQuery()
            ->filterByIdQuote($idQuote)
            ->delete();
    }

    /**
     * @param \DateTime $lifetimeLimitDate
     *
     * @return void
     */
    public function deleteExpiredGuestQuotes(DateTime $lifetimeLimitDate): void
    {
        $query = $this->getFactory()
            ->createQuoteQuery();
        $query->addJoin(SpyQuoteTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN);

        $query->filterByUpdatedAt(['max' => $lifetimeLimitDate], Criteria::LESS_EQUAL)
            ->where(SpyCustomerTableMap::COL_CUSTOMER_REFERENCE . Criteria::ISNULL)
            ->limit(static::BATCH_SIZE_LIMIT);

        do {
            $quoteEntities = $query->find();

            foreach ($quoteEntities as $quoteEntity) {
                $quoteEntity->delete();
            }
        } while ($quoteEntities->count());
    }
}
