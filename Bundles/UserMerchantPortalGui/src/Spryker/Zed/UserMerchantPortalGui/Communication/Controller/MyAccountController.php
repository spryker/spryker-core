<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\MerchantAccountForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\UserMerchantPortalGui\Communication\UserMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\UserMerchantPortalGui\Business\UserMerchantPortalGuiFacadeInterface getFacade()
 */
class MyAccountController extends AbstractController
{
    protected const URL_MERCHANT_MY_ACCOUNT = '/user-merchant-portal-gui/my-account';

    /**
     * @see \Spryker\Zed\UserMerchantPortalGui\Communication\Controller\MyAccountController::indexAction()
     */
    protected const URL_CHANGE_PASSWORD = '/user-merchant-portal-gui/change-password';

    protected const MESSAGE_MERCHANT_USER_UPDATE_SUCCESS = 'Success! The Account is updated.';
    protected const MESSAGE_MERCHANT_USER_UPDATE_ERROR = 'Merchant user entity was not updated.';
    protected const MESSAGE_MERCHANT_USER_VALIDATION_ERROR = 'Please resolve all errors.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $merchantAccountFormDataProvider = $this->getFactory()
            ->createMerchantAccountFormDataProvider();

        $merchantAccountForm = $this->getFactory()
            ->createMerchantAccountForm(
                $merchantAccountFormDataProvider->getData(),
                $merchantAccountFormDataProvider->getOptions()
            )
            ->handleRequest($request);

        $response = [
            'merchantAccountForm' => $merchantAccountForm->createView(),
            'urlChangePassword' => static::URL_CHANGE_PASSWORD,
        ];

        if (!$merchantAccountForm->isSubmitted()) {
            return $this->viewResponse($response);
        }

        if (!$merchantAccountForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_USER_VALIDATION_ERROR);

            return $this->viewResponse($response);
        }

        $this->handleFormSubmission($merchantAccountForm);

        return new RedirectResponse(static::URL_MERCHANT_MY_ACCOUNT);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantAccountForm
     *
     * @return void
     */
    protected function handleFormSubmission(FormInterface $merchantAccountForm): void
    {
        $merchantUserFacade = $this->getFactory()->getMerchantUserFacade();

        $merchantUserTransfer = $merchantUserFacade->getCurrentMerchantUser();
        $merchantUserTransfer->getUserOrFail()
            ->fromArray($merchantAccountForm->getData(), true);

        $merchantUserResponseTransfer = $merchantUserFacade
            ->updateMerchantUser($merchantUserTransfer);

        if (!$merchantUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_USER_UPDATE_ERROR);

            return;
        }

        $merchantUserTransfer = $this->switchLocaleIfChanged($merchantAccountForm, $merchantUserTransfer);
        $merchantUserFacade->setCurrentMerchantUser($merchantUserTransfer);

        $this->getFactory()
            ->createMerchantUserUpdater()
            ->updateMerchantUser($merchantUserTransfer);

        $this->addSuccessMessage(static::MESSAGE_MERCHANT_USER_UPDATE_SUCCESS);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantAccountForm
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function switchLocaleIfChanged(
        FormInterface $merchantAccountForm,
        MerchantUserTransfer $merchantUserTransfer
    ): MerchantUserTransfer {
        if ($this->getIsFkLocaleChanged($merchantAccountForm)) {
            $localeFacade = $this->getFactory()->getLocaleFacade();
            $localeTransfer = $localeFacade->getLocaleById(
                $merchantUserTransfer->getUserOrFail()->getFkLocaleOrFail()
            );

            $localeFacade->setCurrentLocale($localeTransfer);

            $merchantUserTransfer->getUserOrFail()
                ->setFkLocale($localeTransfer->getIdLocale())
                ->setLocaleName($localeTransfer->getLocaleName());
        }

        return $merchantUserTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantAccountForm
     *
     * @return bool
     */
    protected function getIsFkLocaleChanged(FormInterface $merchantAccountForm): bool
    {
        $defaultData = $merchantAccountForm->getConfig()->getData();
        $submittedData = $merchantAccountForm->getData();

        return $defaultData[MerchantAccountForm::FIELD_FK_LOCALE] !== $submittedData[MerchantAccountForm::FIELD_FK_LOCALE];
    }
}
