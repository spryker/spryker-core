<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\Communication\MerchantProfileGuiPageCommunicationFactory getFactory()
 */
class MyMerchantProfileController extends AbstractController
{
    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'The Profile was saved successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $merchantId = $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getIdMerchant();

        $merchantFormDataProvider = $this->getFactory()->createMerchantFormDataProvider();
        $merchantTransfer = $merchantFormDataProvider->getData($merchantId);
        $formOptions = $merchantFormDataProvider->getOptions($merchantTransfer);

        $merchantForm = $this->getFactory()->getMerchantForm($merchantTransfer, $formOptions);
        $merchantForm->handleRequest($request);

        if ($merchantForm->isSubmitted() && $merchantForm->isValid()) {
            $this->updateMerchant($merchantForm);
        }

        return $this->viewResponse([
            'form' => $merchantForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantForm
     *
     * @return void
     */
    protected function updateMerchant(FormInterface $merchantForm): void
    {
        $merchantTransfer = $merchantForm->getData();

        $merchantResponseTransfer = $this->getFactory()
            ->getMerchantFacade()
            ->updateMerchant($merchantTransfer);

        if ($merchantResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_MERCHANT_UPDATE_SUCCESS);

            return;
        }

        foreach ($merchantResponseTransfer->getErrors() as $merchantErrorTransfer) {
            $this->addErrorMessage($merchantErrorTransfer->getMessage());
        }
    }
}
