<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\ElasticBatch;

use Spryker\Zed\DataImport\Business\Model\Memory\SystemMemoryInterface;
use Spryker\Zed\DataImport\DataImportConfig;

class MemoryAllocatedElasticBatch implements ElasticBatchInterface
{
    /**
     * Flag if memory quota is full for current batch size.
     *
     * @var bool
     */
    public static $isMemoryQuotaFull = false;

    /**
     * Counter of batch size incremented with every new batch item.
     *
     * @var int
     */
    protected static $batchCounter = 1;

    /**
     * Ponter of every new batch to check if new batch was started.
     *
     * @var int
     */
    protected static $batchPointer = 1;

    /**
     * Calculated ratio between used memory and peak.
     *
     * @var float
     */
    protected static $memoryPeakFactor = 1.0;

    /**
     * Amount of used memory on a previous batch memory measurement.
     *
     * @var int
     */
    protected static $memoryUsed = 1;

    /**
     * Calculated graduality factor.
     *
     * @var int
     */
    protected static $gradualityFactorCounter;

    /**
     * Memory threshold limit in percentage of total allowed memory.
     *
     * @var int
     */
    protected static $memoryTresholdPercent;

    /**
     * Calculated maximum memory quota to be filled.
     *
     * @var int
     */
    protected static $maxMemoryQuota = 0;

    /**
     * @var int
     */
    protected $usedMemory = 0;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\Memory\SystemMemoryInterface
     */
    protected $systemMemory;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\Memory\SystemMemoryInterface $systemMemory
     * @param \Spryker\Zed\DataImport\DataImportConfig $config
     */
    public function __construct(SystemMemoryInterface $systemMemory, DataImportConfig $config)
    {
        $this->systemMemory = $systemMemory;

        static::$gradualityFactorCounter = $config->getBulkWriteGradualityFactor();
        static::$memoryTresholdPercent = $config->getBulkWriteMemoryThesoldPercent();
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function reset(): void
    {
        static::$isMemoryQuotaFull = false;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isFull(): bool
    {
        if (static::$isMemoryQuotaFull) {
            return true;
        }

        if (static::isNewBatch()) {
            $memoryPeakFactor = $this->calculateMemoryPeakFactor();

            static::setMemoryPeakFactor($memoryPeakFactor);
        }

        $this->usedMemory = $this->systemMemory->getCurrentMemoryUsage();

        if ($this->usedMemory < $this->getMemoryUsedQuota()) {
            return false;
        }

        $this->closeBatch();

        static::$isMemoryQuotaFull = true;

        return true;
    }

    /**
     * Returns true once on every new batch starting from second one.
     *
     * @return bool
     */
    protected static function isNewBatch(): bool
    {
        if (static::$batchPointer === static::$batchCounter) {
            return false;
        }

        static::$batchPointer = static::$batchCounter;

        return true;
    }

    /**
     * Closes batch, saves used memory anf increases a batch counter.
     *
     * @return void
     */
    protected function closeBatch(): void
    {
        static::setMemoryUsed($this->usedMemory);
        static::decreaseGradualityFactorCounter();
        static::$batchCounter++;
    }

    /**
     * @return int
     */
    protected function getMemoryUsed(): int
    {
        return static::$memoryUsed;
    }

    /**
     * @param int $memoryUsed
     *
     * @return void
     */
    protected function setMemoryUsed(int $memoryUsed): void
    {
        static::$memoryUsed = $memoryUsed;
    }

    /**
     * @return void
     */
    protected function decreaseGradualityFactorCounter(): void
    {
        if (static::$gradualityFactorCounter > 1) {
            static::$gradualityFactorCounter--;
        }
    }

    /**
     * Memory used limit for current batch with consideration of peaks and graduality.
     *
     * @return int
     */
    protected function getMemoryUsedQuota(): int
    {
        if (static::$maxMemoryQuota) {
            return static::$maxMemoryQuota;
        }
        $graduatedMemoryQuota = $this->getGraduatedFractionMemoryQuota();

        if (static::$memoryPeakFactor <= 1) {
            return $graduatedMemoryQuota;
        }

        if (static::getExpectedMemoryPeak($graduatedMemoryQuota) <= static::getMemoryLimitWithThreshold()) {
            return $graduatedMemoryQuota;
        }

        static::$maxMemoryQuota = static::getMaxMemoryQuota();
        static::$gradualityFactorCounter = 0;

        return static::$maxMemoryQuota;
    }

    /**
     * Returns max level of memory usage with consideration of limits, threshold and peak factor.
     *
     * @return int
     */
    protected function getMaxMemoryQuota(): int
    {
        return (int)floor(static::getMemoryLimitWithThreshold() / static::getMemoryPeakFactor());
    }

    /**
     * Calculated memory peak for provided amount of actual memory usage.
     *
     * @param int $memoryUsed
     *
     * @return int
     */
    protected function getExpectedMemoryPeak(int $memoryUsed): int
    {
        return (int)floor(static::getMemoryPeakFactor() * $memoryUsed);
    }

    /**
     * Gradually increased fraction of threshold memory limit.
     *
     * @return int
     */
    protected function getGraduatedFractionMemoryQuota(): int
    {
        return (int)($this->getMemoryLimitWithThreshold() / pow(2, static::$gradualityFactorCounter));
    }

    /**
     * Returns allowed memory limit multiplied with usage threshold percent.
     *
     * @return int
     */
    protected function getMemoryLimitWithThreshold(): int
    {
        return (int)floor(static::$memoryTresholdPercent / 100 * $this->systemMemory->getMemoryLimit());
    }

    /**
     * Calculates ratio between the current memory peak and current memory usage level.
     *
     * @return float
     */
    protected function calculateMemoryPeakFactor(): float
    {
        return round($this->systemMemory->getMemoryUsagePeak() / $this->getMemoryUsed(), 2);
    }

    /**
     * @return float
     */
    protected function getMemoryPeakFactor(): float
    {
        return static::$memoryPeakFactor;
    }

    /**
     * @param float $memoryPeakFactor
     *
     * @return void
     */
    protected function setMemoryPeakFactor(float $memoryPeakFactor): void
    {
        static::$memoryPeakFactor = $memoryPeakFactor;
    }
}
