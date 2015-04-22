<?php 

namespace SprykerFeature\Shared\Lumberjack\Transfer;

/**
 *
 */
class MetaRequest extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $requestId = null;

    /**
     * @param string $requestId
     * @return $this
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        $this->addModifiedProperty('requestId');
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }


}
