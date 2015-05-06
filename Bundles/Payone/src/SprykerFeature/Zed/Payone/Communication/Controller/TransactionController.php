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
        /********************************************************************************************************
         * @todo: Payone allways sends status updates in ISO-8859-1 !!!! Do we have to transform???
         ********************************************************************************************************/
        $response = $this->getLocator()->payone()->facade()
            ->processTransactionStatusUpdate($request->request->all());

        $callback = function () use ($response) {
            echo $response;
        };

        /*****************************************
         * @todo: is streamed response correct here?
         *****************************************/
        return $this->streamedResponse($callback);
    }

}
