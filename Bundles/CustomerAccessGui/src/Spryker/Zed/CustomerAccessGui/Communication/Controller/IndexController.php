<?php

namespace Spryker\Zed\CustomerAccessGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CustomerAccessGui\Communication\CustomerAccessGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
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