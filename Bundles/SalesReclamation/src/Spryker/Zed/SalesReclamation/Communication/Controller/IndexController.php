<?php

namespace Spryker\Zed\SalesReclamation\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\SalesReclamation\Business\SalesReclamationFacade getFacade()
 * @method \Spryker\Zed\SalesReclamation\Communication\SalesReclamationCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'test' => 'Greetings!',
        ]);
    }

}
