<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestPersistenceFactory getFactory()
 */
class QuoteRequestEntityManager extends AbstractEntityManager implements QuoteRequestEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function create(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer
    {
        $quoteRequestEntity = $this->getFactory()
            ->createQuoteRequestQuery()
            ->filterByIdQuoteRequest($quoteRequestTransfer->getIdQuoteRequest())
            ->findOneOrCreate();

        $quoteRequestEntity = $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapQuoteRequestTransferToQuoteRequestEntity($quoteRequestTransfer, $quoteRequestEntity);

        $quoteRequestEntity->save();
        $quoteRequestTransfer->setIdQuoteRequest($quoteRequestEntity->getIdQuoteRequest());

        return $quoteRequestTransfer;
    }
}
