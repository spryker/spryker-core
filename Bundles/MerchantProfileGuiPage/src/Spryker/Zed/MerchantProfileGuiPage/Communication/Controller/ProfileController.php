<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'Merchant updated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $userTransfer = $this->getFactory()->getUserFacade()->getCurrentUser();
        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdUser($userTransfer->getIdUser())
        );

        $idMerchant = $merchantUserTransfer->getIdMerchant();

        $dataProvider = $this->getFactory()->createMerchantUpdateFormDataProvider();
        $merchantTransfer = $dataProvider->getData($idMerchant);

        $merchantForm = $this->getFactory()
            ->getMerchantForm(
                $merchantTransfer,
                $dataProvider->getOptions($merchantTransfer)
            )->handleRequest($request);

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
        }

        foreach ($merchantResponseTransfer->getErrors() as $merchantErrorTransfer) {
            $this->addErrorMessage($merchantErrorTransfer->getMessage());
        }
    }
}
