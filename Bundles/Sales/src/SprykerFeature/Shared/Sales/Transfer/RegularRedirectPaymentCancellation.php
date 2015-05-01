<?php 

namespace SprykerFeature\Shared\Sales\Transfer;

/**
 *
 */
class RegularRedirectPaymentCancellation extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $orderId = null;

    /**
     * @param string $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        $this->addModifiedProperty('orderId');
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }


}
