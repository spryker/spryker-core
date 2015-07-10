<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business\Model;

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
     * @return int
     */
    public function getFetchedCount()
    {
        return $this->fetchedCount;
    }

    /**
     * @param int $fetchedCount
     */
    public function setFetchedCount($fetchedCount)
    {
        $this->fetchedCount = (int) $fetchedCount;
    }

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
    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    /**
     * @param int $processedCount
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
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * @param FailedResultInterface $failed
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
     * @param LocaleTransfer $processedLocale
     */
    public function setProcessedLocale(LocaleTransfer $processedLocale)
    {
        $this->processedLocale = $processedLocale;
    }

    /**
     * @param int $amount
     */
    public function increaseProcessedCount($amount)
    {
        $this->processedCount += $amount;
    }

}
