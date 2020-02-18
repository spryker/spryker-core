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

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\Communication\MerchantProfileGuiPageCommunicationFactory getFactory()
 */
class MyMerchantProfileController extends AbstractController
{
    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'Merchant updated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $merchantUserFacade = $this->getFactory()->getMerchantUserFacade();

        $userTransfer = $merchantUserFacade->getCurrentUser();
        $merchantUserTransfer = $merchantUserFacade->findOne(
            (new MerchantUserCriteriaFilterTransfer())->setIdUser($userTransfer->getIdUser())
        );

        //throw exception
        if (!$merchantUserTransfer) {
            $idMerchant = 7;
        } else {
            $idMerchant  = $merchantUserTransfer->getIdMerchant();
        }

        $merchantUpdateFormDataProvider = $this->getFactory()->createMerchantUpdateFormDataProvider();
        $merchantTransfer = $merchantUpdateFormDataProvider->getData($idMerchant);

        $merchantForm = $this->getFactory()
            ->getMerchantForm(
                $merchantTransfer,
                $merchantUpdateFormDataProvider->getOptions($merchantTransfer)
            )->handleRequest($request);

        if ($merchantForm->isSubmitted() && $merchantForm->isValid()) {
            $this->updateMerchant($merchantForm);
        }

        dump($merchantForm->createView());

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
