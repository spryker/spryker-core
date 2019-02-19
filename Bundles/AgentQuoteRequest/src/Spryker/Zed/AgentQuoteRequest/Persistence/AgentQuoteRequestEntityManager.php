<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Persistence;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestPersistenceFactory getFactory()
 */
class AgentQuoteRequestEntityManager extends AbstractEntityManager implements AgentQuoteRequestEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer
    {
        $quoteRequestEntity = $this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->filterByIdQuoteRequest($quoteRequestTransfer->getIdQuoteRequest())
            ->findOne();

        $quoteRequestEntity = $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapQuoteRequestTransferToQuoteRequestEntity($quoteRequestTransfer, $quoteRequestEntity);

        $quoteRequestEntity->save();

        return $quoteRequestTransfer;
    }
}
