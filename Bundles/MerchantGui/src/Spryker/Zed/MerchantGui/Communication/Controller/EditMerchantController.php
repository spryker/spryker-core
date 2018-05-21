<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\MerchantGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotFoundException;
use Spryker\Zed\MerchantGui\Communication\Table\MerchantTableConstants;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class EditMerchantController extends AbstractController
{
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'Merchant has been updated.';
    protected const MESSAGE_MERCHANT_NOT_FOUND = 'Merchant is not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchant = $this->castId($request->get(MerchantTableConstants::REQUEST_ID_MERCHANT));
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, MerchantTableConstants::URL_MERCHANT_LIST);

        $dataProvider = $this->getFactory()->createMerchantFormDataProvider();
        $merchantForm = $this->getFactory()
            ->getMerchantForm(
                $dataProvider->getData($idMerchant),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($merchantForm->isSubmitted() && $merchantForm->isValid()) {
            $merchantTransfer = $merchantForm->getData();
            try {
                $merchantTransfer = $this->getFactory()
                    ->getMerchantFacade()
                    ->updateMerchant($merchantTransfer);

                $this->addSuccessMessage(static::MESSAGE_MERCHANT_UPDATE_SUCCESS);
            } catch (MerchantNotFoundException $exception) {
                $this->addErrorMessage(static::MESSAGE_MERCHANT_NOT_FOUND);
            }

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $merchantForm->createView(),
            'idMerchant' => $idMerchant,
        ]);
    }
}
