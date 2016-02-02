<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;

class BatchResult implements BatchResultInterface
{

    /**
     * @var int
     */
    protected $totalCount = 0;

    /**
     * @var int
     */
    protected $processedCount = 0;

    /**
     * @var int
     */
    protected $fetchedCount = 0;

    /**
     * @var int
     */
    protected $successCount = 0;

    /**
     * @var array|FailedResult[]
     */
    protected $failed = [];

    /**
     * @var int
     */
    protected $failedCount = 0;

    /**
     * @var int
     */
    protected $deletedCount = 0;

    /**
     * @var bool
     */
    protected $isFailed = false;

    /**
     * @var string
     */
    protected $processedLocale = '';

    /**
     * @return int
     */
    public function getFetchedCount()
    {
        return $this->fetchedCount;
    }

    /**
     * @param int $fetchedCount
     *
     * @return void
     */
    public function setFetchedCount($fetchedCount)
    {
        $this->fetchedCount = (int) $fetchedCount;
    }

    /**
     * @return int
     */
    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    /**
     * @param int $processedCount
     *
     * @return void
     */
    public function setProcessedCount($processedCount)
    {
        $this->processedCount = $processedCount;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     *
     * @return void
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * @param FailedResultInterface $failed
     *
     * @return void
     */
    public function addFailedResult(FailedResultInterface $failed)
    {
        $this->failed[] = $failed;
        $this->failedCount += $failed->getFailedCount();
    }

    /**
     * @return int
     */
    public function getFailedCount()
    {
        return $this->failedCount;
    }

    /**
     * @return int
     */
    public function getSuccessCount()
    {
        return $this->totalCount - $this->getFailedCount();
    }

    /**
     * @param int $increment
     *
     * @return void
     */
    public function increaseProcessed($increment = 1)
    {
        $this->processedCount += $increment;
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return $this->isFailed;
    }

    /**
     * @param bool $failed
     *
     * @return void
     */
    public function setIsFailed($failed = true)
    {
        $this->isFailed = $failed;
    }

    /**
     * @return string
     */
    public function getProcessedLocale()
    {
        return $this->processedLocale;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $processedLocale
     *
     * @return void
     */
    public function setProcessedLocale(LocaleTransfer $processedLocale)
    {
        $this->processedLocale = $processedLocale;
    }

    /**
     * @param int $amount
     *
     * @return void
     */
    public function increaseProcessedCount($amount)
    {
        $this->processedCount += $amount;
    }

    /**
     * @return int
     */
    public function getDeletedCount()
    {
        return $this->deletedCount;
    }

    /**
     * @param int $deletedCount
     *
     * @return void
     */
    public function setDeletedCount($deletedCount)
    {
        $this->deletedCount = $deletedCount;
    }

    /**
     * @param int $amount
     *
     * @return void
     */
    public function increaseDeletedCount($amount)
    {
        $this->deletedCount += $amount;
    }

}
