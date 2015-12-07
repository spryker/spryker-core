<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Model;

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
