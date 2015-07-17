<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class OnlineBankTransferContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $onlinebanktransfertype;
    /**
     * @var string
     */
    protected $bankcountry;
    /**
     * @var string
     */
    protected $bankaccount;
    /**
     * @var string
     */
    protected $bankcode;
    /**
     * @var string
     */
    protected $bankgrouptype;
    /**
     * @var string
     */
    protected $iban;
    /**
     * @var string
     */
    protected $bic;
    /**
     * @var RedirectContainer
     */
    protected $redirect;

    /**
     * @param string $bankaccount
     */
    public function setBankAccount($bankaccount)
    {
        $this->bankaccount = $bankaccount;
    }

    /**
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bankaccount;
    }

    /**
     * @param string $bankcode
     */
    public function setBankCode($bankcode)
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankcode;
    }

    /**
     * @param string $bankcountry
     */
    public function setBankCountry($bankcountry)
    {
        $this->bankcountry = $bankcountry;
    }

    /**
     * @return string
     */
    public function getBankCountry()
    {
        return $this->bankcountry;
    }

    /**
     * @param string $bankgrouptype
     */
    public function setBankGroupType($bankgrouptype)
    {
        $this->bankgrouptype = $bankgrouptype;
    }

    /**
     * @return string
     */
    public function getBankGroupType()
    {
        return $this->bankgrouptype;
    }

    /**
     * @param string $onlinebanktransfertype
     */
    public function setOnlineBankTransferType($onlinebanktransfertype)
    {
        $this->onlinebanktransfertype = $onlinebanktransfertype;
    }

    /**
     * @return string
     */
    public function getOnlineBankTransferType()
    {
        return $this->onlinebanktransfertype;
    }

    /**
     * @param RedirectContainer $redirect
     */
    public function setRedirect(RedirectContainer $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return RedirectContainer
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param string $iban
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param string $bic
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

}
