<?php

namespace SprykerFeature\Shared\Auth\Transfer;

class ResetPassword extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idResetPassword = null;
    protected $fkUserId = null;
    protected $code = null;
    protected $status = null;

    /**
     * @return int
     */
    public function getIdResetPassword()
    {
        return $this->idResetPassword;
    }

    /**
     * @param int $idResetPassword
     *
     * @return $this
     */
    public function setIdResetPassword($idResetPassword)
    {
        $this->idResetPassword = $idResetPassword;
        $this->addModifiedProperty('idResetPassword');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkUserId()
    {
        return $this->fkUserId;
    }

    /**
     * @param int $fkUserId
     *
     * @return $this
     */
    public function setFkUserId($fkUserId)
    {
        $this->fkUserId = $fkUserId;
        $this->addModifiedProperty('fkUserId');

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->addModifiedProperty('code');

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->addModifiedProperty('status');

        return $this;
    }
}
