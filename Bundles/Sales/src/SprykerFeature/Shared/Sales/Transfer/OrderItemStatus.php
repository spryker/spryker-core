<?php 

namespace SprykerFeature\Shared\Sales\Transfer;

/**
 *
 */
class OrderItemStatus extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idOmsOrderItemStatus = null;

    protected $name = null;

    protected $description = null;

    /**
     * @param int $idOmsOrderItemStatus
     * @return $this
     */
    public function setIdOmsOrderItemStatus($idOmsOrderItemStatus)
    {
        $this->idOmsOrderItemStatus = $idOmsOrderItemStatus;
        $this->addModifiedProperty('idOmsOrderItemStatus');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdOmsOrderItemStatus()
    {
        return $this->idOmsOrderItemStatus;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->addModifiedProperty('description');
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


}
