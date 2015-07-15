<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business\Model;

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
