<?php

namespace SprykerFeature\Shared\Url\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Redirect extends AbstractTransfer
{
    /**
     * @var int
     */
    protected $idRedirect;

    /**
     * @var string
     */
    protected $toUrl;

    /**
     * @var int
     */
    protected $status = 301;

    /**
     * @return int
     */
    public function getIdRedirect()
    {
        return $this->idRedirect;
    }

    /**
     * @param int $idRedirect
     *
     * @return $this
     */
    public function setIdRedirect($idRedirect)
    {
        $this->addModifiedProperty('idRedirect');
        $this->idRedirect = $idRedirect;

        return $this;
    }

    /**
     * @return string
     */
    public function getToUrl()
    {
        return $this->toUrl;
    }

    /**
     * @param string $toUrl
     *
     * @return $this
     */
    public function setToUrl($toUrl)
    {
        $this->addModifiedProperty('toUrl');
        $this->toUrl = $toUrl;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->addModifiedProperty('status');
        $this->status = $status;

        return $this;
    }
}
