<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProfileController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'The Profile has been changed successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_UPDATE_ERROR = 'The Profile form has errors.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
        $storeTransfers = $merchantUserTransfer->getMerchantOrFail()->getStoreRelationOrFail()->getStores();
        $idMerchant = $merchantUserTransfer->getIdMerchantOrFail();

        $merchantProfileFormDataProvider = $this->getFactory()->createMerchantProfileFormDataProvider();
        $merchantTransfer = $merchantProfileFormDataProvider->findMerchantById($idMerchant);

        $merchantProfileForm = $this->getFactory()->createMerchantProfileForm($merchantTransfer);
        $merchantProfileForm->handleRequest($request);

        if ($merchantProfileForm->isSubmitted()) {
            $this->updateMerchant($merchantProfileForm);
        }

        return $this->viewResponse([
            'form' => $merchantProfileForm->createView(),
            'stores' => $storeTransfers,
        ]);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $merchantForm
     *
     * @param \Symfony\Component\Form\FormInterface $merchantForm
     *
     * @return void
     */
    protected function updateMerchant(FormInterface $merchantForm): void
    {
        if (!$merchantForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_UPDATE_ERROR);

            return;
        }

        $merchantTransfer = $merchantForm->getData();

        $merchantResponseTransfer = $this->getFactory()
            ->getMerchantFacade()
            ->updateMerchant($merchantTransfer);

        if ($merchantResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_MERCHANT_UPDATE_SUCCESS);

            return;
        }

        foreach ($merchantResponseTransfer->getErrors() as $merchantErrorTransfer) {
            /** @var string $message */
            $message = $merchantErrorTransfer->requireMessage()->getMessage();
            $this->addErrorMessage($message);
        }
    }
}
