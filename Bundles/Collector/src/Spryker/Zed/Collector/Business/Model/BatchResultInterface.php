<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;

/**
 * @deprecated Must be refactored into a Transfer object instead.
 */
interface BatchResultInterface
{
    /**
     * @return int
     */
    public function getProcessedCount();

    /**
     * @param int $processedCount
     *
     * @return void
     */
    public function setProcessedCount($processedCount);

    /**
     * @param int $amount
     *
     * @return void
     */
    public function increaseProcessedCount($amount);

    /**
     * @param int $fetchedCount
     *
     * @return void
     */
    public function setFetchedCount($fetchedCount);

    /**
     * @return int
     */
    public function getFetchedCount();

    /**
     * @return int
     */
    public function getTotalCount();

    /**
     * @param int $totalCount
     *
     * @return void
     */
    public function setTotalCount($totalCount);

    /**
     * @param \Spryker\Zed\Collector\Business\Model\FailedResultInterface $failed
     *
     * @return void
     */
    public function addFailedResult(FailedResultInterface $failed);

    /**
     * @return int
     */
    public function getFailedCount();

    /**
     * @return int
     */
    public function getSuccessCount();

    /**
     * @param int $increment
     *
     * @return void
     */
    public function increaseProcessed($increment = 1);

    /**
     * @return bool
     */
    public function isFailed();

    /**
     * @param bool $failed
     *
     * @return void
     */
    public function setIsFailed($failed = true);

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function getProcessedLocale();

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $processedLocale
     *
     * @return void
     */
    public function setProcessedLocale(LocaleTransfer $processedLocale);

    /**
     * @return int
     */
    public function getDeletedCount();

    /**
     * @param int $deletedCount
     *
     * @return void
     */
    public function setDeletedCount($deletedCount);

    /**
     * @param int $amount
     *
     * @return void
     */
    public function increaseDeletedCount($amount);
}
