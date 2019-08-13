<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestPersistenceFactory getFactory()
 */
class QuoteRequestEntityManager extends AbstractEntityManager implements QuoteRequestEntityManagerInterface
{
    protected const COLUMN_STATUS = 'Status';

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer
    {
        $quoteRequestEntity = $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapQuoteRequestTransferToQuoteRequestEntity($quoteRequestTransfer, new SpyQuoteRequest());

        $quoteRequestEntity->save();
        $quoteRequestTransfer->setIdQuoteRequest($quoteRequestEntity->getIdQuoteRequest());

        return $quoteRequestTransfer;
    }

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

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function createQuoteRequestVersion(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): QuoteRequestVersionTransfer
    {
        $quoteRequestVersionEntity = $this->getFactory()
            ->createQuoteRequestVersionMapper()
            ->mapQuoteRequestVersionTransferToQuoteRequestVersionEntity($quoteRequestVersionTransfer, new SpyQuoteRequestVersion());

        $quoteRequestVersionEntity->save();
        $quoteRequestVersionTransfer->setIdQuoteRequestVersion($quoteRequestVersionEntity->getIdQuoteRequestVersion());

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function updateQuoteRequestVersion(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): QuoteRequestVersionTransfer
    {
        $quoteRequestVersionEntity = $this->getFactory()
            ->getQuoteRequestVersionPropelQuery()
            ->filterByIdQuoteRequestVersion($quoteRequestVersionTransfer->getIdQuoteRequestVersion())
            ->findOne();

        $quoteRequestVersionEntity = $this->getFactory()
            ->createQuoteRequestVersionMapper()
            ->mapQuoteRequestVersionTransferToQuoteRequestVersionEntity($quoteRequestVersionTransfer, $quoteRequestVersionEntity);

        $quoteRequestVersionEntity->save();

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \DateTime $validUntil
     *
     * @return void
     */
    public function closeOutdatedQuoteRequests(DateTime $validUntil): void
    {
        $this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->filterByStatus(SharedQuoteRequestConfig::STATUS_READY)
            ->filterByValidUntil($validUntil, Criteria::LESS_EQUAL)
            ->update([static::COLUMN_STATUS => SharedQuoteRequestConfig::STATUS_CLOSED]);
    }

    /**
     * @param string $quoteRequestReference
     * @param string $fromStatus
     * @param string $toStatus
     *
     * @return bool
     */
    public function updateQuoteRequestStatus(string $quoteRequestReference, string $fromStatus, string $toStatus): bool
    {
        return (bool)$this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->filterByQuoteRequestReference($quoteRequestReference)
            ->filterByStatus($fromStatus)
            ->update([static::COLUMN_STATUS => $toStatus]);
    }

    /**
     * @param int[] $quoteRequestIds
     *
     * @return void
     */
    public function deleteQuoteRequestsByIds(array $quoteRequestIds): void
    {
        $this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->filterByIdQuoteRequest_In($quoteRequestIds)
            ->delete();
    }

    /**
     * @param int[] $quoteRequestIds
     *
     * @return void
     */
    public function deleteQuoteRequestVersionsByQuoteRequestIds(array $quoteRequestIds): void
    {
        $this->getFactory()
            ->getQuoteRequestVersionPropelQuery()
            ->filterByFkQuoteRequest_In($quoteRequestIds)
            ->delete();
    }
}
