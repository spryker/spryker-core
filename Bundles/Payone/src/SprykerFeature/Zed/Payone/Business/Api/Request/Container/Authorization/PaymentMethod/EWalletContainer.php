<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class EWalletContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $wallettype;
    /**
     * @var RedirectContainer
     */
    protected $redirect;

    /**
     * @param string $wallettype
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

}
