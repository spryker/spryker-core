<?php

namespace SprykerFeature\Zed\Payone\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use Symfony\Component\HttpFoundation\Request;

class TransactionStatusUpdateController extends AbstractController implements PayoneApiConstants
{

    public function statusUpdateAction(Request $request)
    {
        $this->getLocator()->payone()->facade()->processTransactionStatusUpdate($request->request->all());
    }

}
