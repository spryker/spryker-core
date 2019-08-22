<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotFoundException;
use Spryker\Zed\MerchantGui\Communication\Table\MerchantTableConstants;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class EditMerchantController extends AbstractController
{
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'Merchant updated successfully.';
    protected const MESSAGE_MERCHANT_NOT_FOUND = 'Merchant is not found.';
    protected const MESSAGE_MERCHANT_ERROR = 'Merchant was not updated.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchant = $this->castId($request->get(MerchantTableConstants::REQUEST_ID_MERCHANT));

        $dataProvider = $this->getFactory()->createMerchantUpdateFormDataProvider();
        $merchantTransfer = $dataProvider->getData($idMerchant);

        if ($merchantTransfer === null) {
            $this->addErrorMessage("Merchant with id %s doesn't exists.", ['%s' => $idMerchant]);

            return $this->redirectResponse(MerchantTableConstants::URL_MERCHANT_LIST);
        }

        $merchantForm = $this->getFactory()
            ->getMerchantUpdateForm(
                $merchantTransfer,
                $dataProvider->getOptions($merchantTransfer)
            )
            ->handleRequest($request);

        if ($merchantForm->isSubmitted() && $merchantForm->isValid()) {
            return $this->updateMerchant($request, $merchantForm);
        }

        return $this->viewResponse([
            'form' => $merchantForm->createView(),
            'idMerchant' => $idMerchant,
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
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, MerchantTableConstants::URL_MERCHANT_LIST);
        $merchantTransfer = $merchantForm->getData();
        try {
            $merchantResponseTransfer = $this->getFactory()
                ->getMerchantFacade()
                ->updateMerchant($merchantTransfer);

            if ($merchantResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(static::MESSAGE_MERCHANT_UPDATE_SUCCESS);

                return $this->redirectResponse($redirectUrl);
            }
        } catch (MerchantNotFoundException $exception) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_NOT_FOUND);
        }

        $this->addErrorMessage(static::MESSAGE_MERCHANT_ERROR);

        return $this->redirectResponse($redirectUrl);
    }
}
