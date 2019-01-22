<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion;
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
    public function saveQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer
    {
        $quoteRequestEntity = $this->getFactory()
            ->createQuoteRequestQuery()
            ->filterByIdQuoteRequest($quoteRequestTransfer->getIdQuoteRequest())
            ->findOne();

        if ($quoteRequestEntity !== null) {
            return $this->updateQuoteRequest($quoteRequestTransfer, $quoteRequestEntity);
        }

        return $this->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function saveQuoteRequestVersion(
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer
    ): QuoteRequestVersionTransfer {
        $quoteRequestVersionEntity = $this->getFactory()
            ->createQuoteRequestVersionQuery()
            ->filterByIdQuoteRequestVersion($quoteRequestVersionTransfer->getIdQuoteRequestVersion())
            ->findOne();

        if ($quoteRequestVersionEntity !== null) {
            return $this->updateQuoteRequestVersion($quoteRequestVersionTransfer, $quoteRequestVersionEntity);
        }

        return $this->createQuoteRequestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequest(
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer {
        $quoteRequestEntity = $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapQuoteRequestTransferToQuoteRequestEntity($quoteRequestTransfer, new SpyQuoteRequest());

        $quoteRequestEntity->save();
        $quoteRequestTransfer->setIdQuoteRequest($quoteRequestEntity->getIdQuoteRequest());

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function updateQuoteRequest(
        QuoteRequestTransfer $quoteRequestTransfer,
        SpyQuoteRequest $quoteRequestEntity
    ): QuoteRequestTransfer {
        $quoteRequestEntity = $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapQuoteRequestTransferToQuoteRequestEntity($quoteRequestTransfer, $quoteRequestEntity);

        $quoteRequestEntity->save();

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function createQuoteRequestVersion(
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer
    ): QuoteRequestVersionTransfer {
        $quoteRequestVersionEntity = $this->getFactory()
            ->createQuoteRequestVersionMapper()
            ->mapQuoteRequestVersionTransferToQuoteRequestVersionEntity($quoteRequestVersionTransfer, new SpyQuoteRequestVersion());

        $quoteRequestVersionEntity->save();
        $quoteRequestVersionTransfer->setIdQuoteRequestVersion($quoteRequestVersionEntity->getIdQuoteRequestVersion());

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersionEntity
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function updateQuoteRequestVersion(
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer,
        SpyQuoteRequestVersion $quoteRequestVersionEntity
    ): QuoteRequestVersionTransfer {
        $quoteRequestVersionEntity = $this->getFactory()
            ->createQuoteRequestVersionMapper()
            ->mapQuoteRequestVersionTransferToQuoteRequestVersionEntity($quoteRequestVersionTransfer, $quoteRequestVersionEntity);

        $quoteRequestVersionEntity->save();

        return $quoteRequestVersionTransfer;
    }
}
