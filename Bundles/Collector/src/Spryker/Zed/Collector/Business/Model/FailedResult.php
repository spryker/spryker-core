<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Model;

/**
 * @deprecated Must be refactored into a Transfer object instead.
 */
class FailedResult implements FailedResultInterface
{
    /**
     * @var string
     */
    protected $reason = '';

    /**
     * @var int
     */
    protected $firstId;

    /**
     * @var int
     */
    protected $lastId;

    /**
     * @var string
     */
    protected $itemType;

    /**
     * @var string
     */
    protected $processor;

    /**
     * @var int
     */
    protected $failedCount = 0;

    /**
     * @return int
     */
    public function getFirstId()
    {
        return $this->firstId;
    }

    /**
     * @param int $itemId
     *
     * @return void
     */
    public function setFirstId($itemId)
    {
        $this->firstId = $itemId;
    }

    /**
     * @return string
     */
    public function getItemType()
    {
        return $this->itemType;
    }

    /**
     * @param string $itemType
     *
     * @return void
     */
    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     *
     * @return void
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return int
     */
    public function getLastId()
    {
        return $this->lastId;
    }

    /**
     * @param int $lastId
     *
     * @return void
     */
    public function setLastId($lastId)
    {
        $this->lastId = $lastId;
    }

    /**
     * @param int $count
     *
     * @return void
     */
    public function setFailedCount($count)
    {
        $this->failedCount = $count;
    }

    /**
     * @return int
     */
    public function getFailedCount()
    {
        return $this->failedCount;
    }
}
