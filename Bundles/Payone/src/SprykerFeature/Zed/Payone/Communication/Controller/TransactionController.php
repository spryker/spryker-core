<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method PayoneFacade getFacade()
 */
class TransactionController extends AbstractController implements PayoneApiConstants
{

    /**
     * @param Request $request
     *
     * @return StreamedResponse
     */
    public function statusUpdateAction(Request $request)
    {
        //Payone always sends status updates in ISO-8859-1. We transform them to utf8.
        $requestParameters = $request->request->all();
        foreach ($requestParameters as $requestParameterKey => $requestParameter) {
            $requestParameters[$requestParameterKey] = utf8_encode($requestParameter);
        }

        $response = $this->getFacade()
            ->processTransactionStatusUpdate($requestParameters);

        $callback = function () use ($response) {
            echo $response;
        };

        /*****************************************
         * @todo: is streamed response correct here?
         *****************************************/
        return $this->streamedResponse($callback);
    }

}
