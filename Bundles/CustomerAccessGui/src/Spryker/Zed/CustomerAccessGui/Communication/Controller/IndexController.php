<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerAccessGui\Communication\CustomerAccessGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $customerAccessDataProvider = $this->getFactory()->createCustomerAccessDataProvider(
            $this->getFactory()->getCustomerAccessFacade()->findUnauthenticatedCustomerAccess()
        );

        $customerAccessForm = $this->getFactory()->getCustomerAccessForm($customerAccessDataProvider->getData());

        if ($request->request->count() > 0) {
            $customerAccessForm->handleRequest($request);

            if ($customerAccessForm->isSubmitted() && $customerAccessForm->isValid()) {
                $this->getFactory()
                    ->getCustomerAccessFacade()
                    ->updateOnlyContentTypesToAccessible($customerAccessForm->getData());
                $this->addSuccessMessage('Not logged in customer accessible content has been successfully updated.');
            }
        }

        return [
            'form' => $customerAccessForm->createView(),
        ];
    }
}
