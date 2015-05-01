<?php 

namespace SprykerFeature\Shared\Sales\Transfer;

/**
 *
 */
class Comment extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idSalesOrderComment = null;

    protected $message = null;

    protected $username = null;

    protected $fkSalesOrder = null;

    protected $createdAt = null;

    protected $updatedAt = null;

    /**
     * @param int $idSalesOrderComment
     * @return $this
     */
    public function setIdSalesOrderComment($idSalesOrderComment)
    {
        $this->idSalesOrderComment = $idSalesOrderComment;
        $this->addModifiedProperty('idSalesOrderComment');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesOrderComment()
    {
        return $this->idSalesOrderComment;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        $this->addModifiedProperty('message');
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        $this->addModifiedProperty('username');
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param int $fkSalesOrder
     * @return $this
     */
    public function setFkSalesOrder($fkSalesOrder)
    {
        $this->fkSalesOrder = $fkSalesOrder;
        $this->addModifiedProperty('fkSalesOrder');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkSalesOrder()
    {
        return $this->fkSalesOrder;
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        $this->addModifiedProperty('createdAt');
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        $this->addModifiedProperty('updatedAt');
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


}
