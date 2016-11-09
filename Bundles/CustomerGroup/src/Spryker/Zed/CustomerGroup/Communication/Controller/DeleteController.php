<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Controller;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacade getFacade()
 * @method \Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainer getQueryContainer()
 */
class DeleteController extends EditController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCustomerGroup = $this->castId($request->get(static::PARAM_ID_CUSTOMER_GROUP));

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($idCustomerGroup);

        $this->getFacade()->delete(
            $customerGroupTransfer
        );

        return $this->redirectResponse('/customer-group');
    }

}
