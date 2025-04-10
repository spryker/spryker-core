<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 */
abstract class AbstractCustomerMultiFactorAuthController extends AbstractController
{
    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(Request $request, ?string $formName = null): CustomerTransfer
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer !== null) {
            return $customerTransfer;
        }

        $email = $this->getParameterFromRequest($request, CustomerTransfer::EMAIL, $formName);
        $customerTransfer = (new CustomerTransfer())->setEmail($email);

        return $this->getFactory()->getCustomerClient()->getCustomerByEmail($customerTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $parameter
     * @param string|null $formName
     *
     * @return mixed
     */
    protected function getParameterFromRequest(Request $request, string $parameter, ?string $formName = null): mixed
    {
        return $this->getFactory()->createRequestReader()->get($request, $parameter, $formName);
    }
}
