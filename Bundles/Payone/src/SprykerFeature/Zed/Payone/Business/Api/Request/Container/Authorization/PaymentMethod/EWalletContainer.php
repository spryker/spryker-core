<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class EWalletContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $wallettype;
    /**
     * @var \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    protected $redirect;


    /**
     * @param $wallettype
     */
    public function setWalletType($wallettype)
    {
        $this->wallettype = $wallettype;
    }

    /**
     * @return string
     */
    public function getWalletType()
    {
        return $this->wallettype;
    }

    /**
     * @param \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer $redirect
     */
    public function setRedirect(RedirectContainer $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

}
