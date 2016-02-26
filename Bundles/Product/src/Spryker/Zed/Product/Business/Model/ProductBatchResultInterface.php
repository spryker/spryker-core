<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Model;

interface ProductBatchResultInterface
{

    /**
     * @return int
     */
    public function getFailedCount();

    /**
     * @param int $failed
     *
     * @return void
     */
    public function setFailedCount($failed);

    /**
     * @param int $incrementCount
     *
     * @return void
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
     *
     * @return void
     */
    public function setTotalCount($total);

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

}
