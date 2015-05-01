<?php 

namespace SprykerFeature\Shared\Mail\Transfer;

/**
 *
 */
class Customer extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $customerId = null;

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        $this->addModifiedProperty('customerId');
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }


}
