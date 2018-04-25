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
    protected const MESSAGE_UPDATE_SUCCESS = 'Not logged in customer accessible content has been successfully updated.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $customerAccessDataProvider = $this->getFactory()->createCustomerAccessDataProvider(
            $this->getFactory()->getCustomerAccessFacade()->getContentTypesWithUnauthenticatedCustomerAccess()
        );

        $customerAccessForm = $this->getFactory()->getCustomerAccessForm($customerAccessDataProvider->getData(), $customerAccessDataProvider->getOptions());

        $customerAccessForm->handleRequest($request);

        if ($customerAccessForm->isSubmitted() && $customerAccessForm->isValid()) {
            $this->getFactory()
                ->getCustomerAccessFacade()
                ->updateUnauthenticatedCustomerAccess($customerAccessForm->getData());
            $this->addSuccessMessage(static::MESSAGE_UPDATE_SUCCESS);
        }

        return [
            'form' => $customerAccessForm->createView(),
        ];
    }
}
