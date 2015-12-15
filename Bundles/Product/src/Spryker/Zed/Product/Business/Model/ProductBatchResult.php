<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Model;

class ProductBatchResult implements ProductBatchResultInterface
{

    /**
     * @var int
     */
    protected $failed = 0;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var bool
     */
    protected $isFailed = false;

    /**
     * @return int
     */
    public function getFailedCount()
    {
        return $this->failed;
    }

    /**
     * @param int $failed
     *
     * @return void
     */
    public function setFailedCount($failed)
    {
        $this->failed = $failed;
    }

    /**
     * @param int $incrementCount
     *
     * @return void
     */
    public function increaseFailed($incrementCount = 1)
    {
        $this->failed += $incrementCount;
    }

    /**
     * @return int
     */
    public function getSuccessCount()
    {
        if (!$this->isFailed()) {
            return $this->getTotalCount() - $this->getFailedCount();
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->total;
    }

    /**
     * @param int $total
     *
     * @return void
     */
    public function setTotalCount($total)
    {
        $this->total = $total;
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

}
