<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class FinancingContainer extends AbstractPaymentMethodContainer
{

    /**
     * Enum FinancingType
     *
     * @var string
     */
    protected $financingtype;

    /**
     * @var \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    protected $redirect;

    /**
     * @param string $financingtype
     *
     * @return void
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
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer $redirect
     *
     * @return void
     */
    public function setRedirect(RedirectContainer $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

}
