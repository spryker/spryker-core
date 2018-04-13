<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuotePersistenceFactory getFactory()
 */
class QuoteEntityManager extends AbstractEntityManager implements QuoteEntityManagerInterface
{
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
}
