<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Model;

interface FailedResultInterface
{

    /**
     * @return int
     */
    public function getFirstId();

    /**
     * @param int $itemId
     */
    public function setFirstId($itemId);

    /**
     * @return string
     */
    public function getItemType();

    /**
     * @param string $itemType
     */
    public function setItemType($itemType);

    /**
     * @return string
     */
    public function getReason();

    /**
     * @param string $reason
     */
    public function setReason($reason);

    /**
     * @return int
     */
    public function getLastId();

    /**
     * @param int $lastId
     */
    public function setLastId($lastId);

    /**
     * @param int $count
     */
    public function setFailedCount($count);

    /**
     * @return int
     */
    public function getFailedCount();

}
