<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantGui\MerchantGuiConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class EditMerchantController extends AbstractController
{
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';
    public const REQUEST_ID_MERCHANT = 'id-merchant';

    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'Merchant updated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchant = $this->castId($request->get(static::REQUEST_ID_MERCHANT));

        $dataProvider = $this->getFactory()->createMerchantUpdateFormDataProvider();
        $merchantTransfer = $dataProvider->getData($idMerchant);

        if ($merchantTransfer === null) {
            $this->addErrorMessage("Merchant with id %s doesn't exists.", ['%s' => $idMerchant]);

            return $this->redirectResponse(MerchantGuiConfig::URL_MERCHANT_LIST);
        }

        $merchantForm = $this->getFactory()
            ->getMerchantForm(
                $merchantTransfer,
                $dataProvider->getOptions($merchantTransfer)
            )
            ->handleRequest($request);

        $applicableMerchantStatuses = $this->getFactory()->getMerchantFacade()->getApplicableMerchantStatuses($merchantTransfer->getStatus());

        if ($merchantForm->isSubmitted() && $merchantForm->isValid()) {
            return $this->updateMerchant($request, $merchantForm);
        }

        return $this->viewResponse([
            'form' => $merchantForm->createView(),
            'idMerchant' => $idMerchant,
            'applicableMerchantStatuses' => $applicableMerchantStatuses,
            'merchantFormTabs' => $this->getFactory()->createMerchantFormTabs()->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $merchantForm
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function updateMerchant(Request $request, FormInterface $merchantForm)
    {
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, MerchantGuiConfig::URL_MERCHANT_LIST);
        $merchantTransfer = $merchantForm->getData();

        $merchantResponseTransfer = $this->getFactory()
            ->getMerchantFacade()
            ->updateMerchant($merchantTransfer);

        if ($merchantResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_MERCHANT_UPDATE_SUCCESS);

            return $this->redirectResponse($redirectUrl);
        }

        foreach ($merchantResponseTransfer->getErrors() as $merchantErrorTransfer) {
            $this->addErrorMessage($merchantErrorTransfer->getMessage());
        }

        return $this->redirectResponse($redirectUrl);
    }
}
