<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ViewController extends AbstractController
{
    public function indexAction(Request $request)
    {
        $idCustomer = $request->get('id_customer');

        return $this->viewResponse([
            'id_customer' => $idCustomer,
        ]);
    }
}
