<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

class Comment
{
    /**
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderCommentQuery
     */
    public function getQuery()
    {
        return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderCommentQuery::create();
    }

    /**
     * @param int $orderId
     * @return array|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderComment
     */
    public function getCommentsByOrderId($orderId)
    {
        assert(is_int($orderId));

        return $this->getQuery()->firstCreatedFirst()->findByFkSalesOrder($orderId);
    }

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\CommentCollection $collection
     * @return mixed
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function save(\SprykerFeature\Shared\Sales\Transfer\CommentCollection $collection)
    {
        $connection = \Propel\Runtime\Propel::getConnection();
        $connection->beginTransaction();
        try {
            /** @var $transfer \SprykerFeature\Shared\Sales\Transfer\Comment */
            foreach($collection as $transfer) {
                $entity = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderCommentQuery::create()->findPk($transfer->getIdSalesOrderComment());
                if($entity) {
                    throw new \ErrorException('Not allowed to modify existing comments!');
                }
                $entity = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderComment();
                $entity->fromArray($transfer->toArray());
                $entity->setUsername($this->getAclUsername());
                if($entity->isModified()) {
                    $entity->save();
                    $transfer->setIdSalesOrderComment($entity->getPrimaryKey());
                }
            }
            $connection->commit();
        } catch(\PropelException $e) {
            $connection->rollBack();
            throw $e;
        }
        return $collection;
    }

    /**
     * @return null|string
     */
    protected function getAclUsername()
    {
        $auth = Auth::getInstance();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity()->getUsername();
        }
        return null;
    }

}
