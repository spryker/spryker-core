<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Reader;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface;

class ReturnReader implements ReturnReaderInterface
{
    protected const GLOSSARY_KEY_RETURN_NOT_EXISTS = 'return.validation.error.not_exists';

    /**
     * @var \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface
     */
    protected $salesReturnRepository;

    /**
     * @param \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface $salesReturnRepository
     */
    public function __construct(SalesReturnRepositoryInterface $salesReturnRepository)
    {
        $this->salesReturnRepository = $salesReturnRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function getReturn(ReturnFilterTransfer $returnFilterTransfer): ReturnResponseTransfer
    {
        $returnFilterTransfer->requireReturnReference();

        $returnTransfer = $this
            ->getReturnCollection($returnFilterTransfer)
            ->getReturns()
            ->getIterator()
            ->current();

        if (!$returnTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_RETURN_NOT_EXISTS);
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true)
            ->setReturn($returnTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturnCollection(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        $returnCollectionTransfer = $this->salesReturnRepository->getReturnCollectionByFilter($returnFilterTransfer);

        $returnCollectionTransfer = $this->expandReturnCollectionWithReturnItems($returnCollectionTransfer);
        $returnCollectionTransfer = $this->expandReturnCollectionWithReturnTotals($returnCollectionTransfer);

        return $returnCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    protected function expandReturnCollectionWithReturnItems(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer
    {
        // TODO: in next story.

        return $returnCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    protected function expandReturnCollectionWithReturnTotals(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer
    {
        // TODO: in next story.

        return $returnCollectionTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function getErrorResponse(string $message): ReturnResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
