<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Model;

interface ProductBatchResultInterface
{

    /**
     * @return int
     */
    public function getFailedCount();

    /**
     * @param int $failed
     */
    public function setFailedCount($failed);

    /**
     * @param int $incrementCount
     */
    public function increaseFailed($incrementCount = 1);

    /**
     * @return int
     */
    public function getSuccessCount();

    /**
     * @return int
     */
    public function getTotalCount();

    /**
     * @param int $total
     */
    public function setTotalCount($total);

    /**
     * @return bool
     */
    public function isFailed();

    /**
     * @param bool $failed
     */
    public function setIsFailed($failed = true);

}
