<?php 

namespace SprykerFeature\Shared\Cart\Transfer;

/**
 *
 */
class StepStorage extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $userId = null;

    protected $stepName = null;

    protected $isSuccess = null;

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        $this->addModifiedProperty('userId');
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $stepName
     * @return $this
     */
    public function setStepName($stepName)
    {
        $this->stepName = $stepName;
        $this->addModifiedProperty('stepName');
        return $this;
    }

    /**
     * @return string
     */
    public function getStepName()
    {
        return $this->stepName;
    }

    /**
     * @param bool $isSuccess
     * @return $this
     */
    public function setIsSuccess($isSuccess)
    {
        $this->isSuccess = $isSuccess;
        $this->addModifiedProperty('isSuccess');
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }


}
