<?php

namespace SprykerFeature\Zed\Payone\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use Symfony\Component\HttpFoundation\Request;

class TransactionController extends AbstractController implements PayoneApiConstants
{

    /**
     * @param Request $request
     */
    public function statusUpdateAction(Request $request)
    {
        $response = $this->getLocator()->payone()->facade()
            ->processTransactionStatusUpdate($request->request->all());

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

}
