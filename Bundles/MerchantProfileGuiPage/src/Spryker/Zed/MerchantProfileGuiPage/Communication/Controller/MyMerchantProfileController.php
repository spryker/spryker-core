<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Exception\MerchantUserNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\Communication\MerchantProfileGuiPageCommunicationFactory getFactory()
 */
class MyMerchantProfileController extends AbstractController
{
    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'Merchant updated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idMerchant = $this->getIdMerchant();

        $merchantFormDataProvider = $this->getFactory()->createMerchantFormDataProvider();
        $merchantTransfer = $merchantFormDataProvider->getData($idMerchant);

        $merchantForm = $this->getFactory()
            ->getMerchantForm(
                $merchantTransfer,
                $merchantFormDataProvider->getOptions($merchantTransfer)
            )->handleRequest($request);

        if ($merchantForm->isSubmitted() && $merchantForm->isValid()) {
            $this->updateMerchant($merchantForm);
        }

        return $this->viewResponse([
            'form' => $merchantForm->createView(),
        ]);
    }

    /**
     * @throws \Spryker\Zed\MerchantProfileGuiPage\Communication\Exception\MerchantUserNotFoundException
     *
     * @return int
     */
    protected function getIdMerchant(): int
    {
        $merchantUserFacade = $this->getFactory()->getMerchantUserFacade();

        $idUser = $merchantUserFacade->getCurrentUser()->getIdUser();
        $merchantUserTransfer = $merchantUserFacade->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdUser($idUser)
        );

        if (!$merchantUserTransfer) {
            throw new MerchantUserNotFoundException();
        }

        return $merchantUserTransfer->getIdMerchant();
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
