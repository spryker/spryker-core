<?php

namespace Spryker\Zed\CustomerAccessGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CustomerAccessGui\Communication\CustomerAccessGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return [
            'customerAccess' => $this->getFactory()->getCustomerAccessFacade()->findUnauthenticatedCustomerAccess(),
        ];
    }
}