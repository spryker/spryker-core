<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class FinancingContainer extends AbstractPaymentMethodContainer
{

    /**
     * Enum FinancingType
     *
     * @var string
     */
    protected $financingtype;
    /**
     * @var \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    protected $redirect;

    /**
     * @param string $financingtype
     */
    public function setFinancingType($financingtype)
    {
        $this->financingtype = $financingtype;
    }

    /**
     * @return string
     */
    public function getFinancingType()
    {
        return $this->financingtype;
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
