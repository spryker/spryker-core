<?php

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
        /********************************************************************************************************
         * @todo: Payone allways sends status updates in ISO-8859-1 !!!! Do we have to transform???
         ********************************************************************************************************/
        $response = $this->getFacade()
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
