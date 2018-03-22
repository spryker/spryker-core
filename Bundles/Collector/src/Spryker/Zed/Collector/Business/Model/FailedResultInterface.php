<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Model;

/**
 * @deprecated Must be refactored into a Transfer object instead.
 */
interface FailedResultInterface
{
    /**
     * @return int
     */
    public function getFirstId();

    /**
     * @param int $itemId
     *
     * @return void
     */
    public function setFirstId($itemId);

    /**
     * @return string
     */
    public function getItemType();

    /**
     * @param string $itemType
     *
     * @return void
     */
    public function setItemType($itemType);

    /**
     * @return string
     */
    public function getReason();

    /**
     * @param string $reason
     *
     * @return void
     */
    public function setReason($reason);

    /**
     * @return int
     */
    public function getLastId();

    /**
     * @param int $lastId
     *
     * @return void
     */
    public function setLastId($lastId);

    /**
     * @param int $count
     *
     * @return void
     */
    public function setFailedCount($count);

    /**
     * @return int
     */
    public function getFailedCount();
}
