<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

use SprykerFeature\Zed\Auth\Business\Model\Auth;

class SprykerFeature_Zed_Sales_Business_Model_OrderNote
{

    /**
     * @var array
     */
    protected static $notes = [];

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return array|mixed|PropelObjectCollection
     */
    public function getNotes(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $entities = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNoteQuery::create()
            ->filterByOrder($order)
            ->orderBy(\SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderNoteTableMap::COL_ID_SALES_ORDER_NOTE, Criteria::DESC)
            ->find();
        return $entities;
    }

    /**
     * @param $message
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param $isSuccess
     * @param $commandClassName
     */
    public function addNote($message, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, $isSuccess, $commandClassName)
    {
        assert(is_string($message));
        assert(is_string($commandClassName));

        if (strlen($message) > 254) {
            $message = substr($message, 0, 250).' ...';
        }

        $entity = $this->getEntity();
        $entity->setCommand($commandClassName);
        $entity->setMessage($message);
        $entity->setSuccess($isSuccess);
        $entity->setOrder($orderEntity);
        $entity->setAclUser(Auth::getInstance()->getCurrentUser());

        self::$notes[] = $entity;
    }

    /**
     * @param $message
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param bool $isSuccess
     * @param $commandClassName
     */
    public function saveNote($message, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, $isSuccess = true, $commandClassName)
    {
        $this->addNote($message, $orderEntity, $isSuccess, $commandClassName);
        $this->saveAllNotes();
    }

    public function saveAllNotes()
    {
        foreach (self::$notes as $note) {
            $note->save();
        }
        self::$notes = [];
    }

    /**
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNote
     */
    protected function getEntity()
    {
        return new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNote();
    }
}
