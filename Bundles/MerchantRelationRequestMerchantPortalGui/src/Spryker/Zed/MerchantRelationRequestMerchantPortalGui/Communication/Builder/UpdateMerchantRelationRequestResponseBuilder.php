<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToGlossaryFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface;
use Symfony\Component\Form\FormInterface;

class UpdateMerchantRelationRequestResponseBuilder implements UpdateMerchantRelationRequestResponseBuilderInterface
{
    /**
     * @var \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    protected ZedUiFactoryInterface $zedUiFactory;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToGlossaryFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade;

    /**
     * @param \Spryker\Shared\ZedUi\ZedUiFactoryInterface $zedUiFactory
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        ZedUiFactoryInterface $zedUiFactory,
        MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        MerchantRelationRequestMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->zedUiFactory = $zedUiFactory;
        $this->translatorFacade = $translatorFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'The Merchant Relation Request is saved.';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_ERROR = 'To save the Merchant Relation Request please resolve all errors.';

    /**
     * @param array<string, mixed> $responseData
     *
     * @return array<string, mixed>
     */
    public function addSuccessResponseDataToResponse(array $responseData): array
    {
        $notification = $this->translatorFacade->trans(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS);

        $zedUiFormResponseTransfer = $this->zedUiFactory
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification($notification)
            ->addActionCloseDrawer()
            ->addActionRefreshTable()
            ->createResponse();

        return array_merge($responseData, $zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param array<string, mixed> $responseData
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestForm
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer|null $merchantRelationRequestCollectionResponseTransfer
     *
     * @return array<string, mixed>
     */
    public function addErrorResponseDataToResponse(
        array $responseData,
        FormInterface $merchantRelationRequestForm,
        ?MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer = null
    ): array {
        $notification = $this->translatorFacade->trans(static::RESPONSE_NOTIFICATION_MESSAGE_ERROR);

        $zedUiFormResponseBuilder = $this->zedUiFactory
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($notification);

        $zedUiFormResponseBuilder = $this->addFormErrors($merchantRelationRequestForm, $zedUiFormResponseBuilder);

        if ($merchantRelationRequestCollectionResponseTransfer && $merchantRelationRequestCollectionResponseTransfer->getErrors()->count()) {
            foreach ($merchantRelationRequestCollectionResponseTransfer->getErrors() as $errorTransfer) {
                $zedUiFormResponseBuilder->addErrorNotification($this->glossaryFacade->translate(
                    $errorTransfer->getMessageOrFail(),
                ));
            }
        }

        return array_merge($responseData, $zedUiFormResponseBuilder->createResponse()->toArray());
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface $zedUiFormResponseBuilder
     *
     * @return \Spryker\Shared\ZedUi\Configuration\ZedUiFormResponseBuilderInterface
     */
    protected function addFormErrors(
        FormInterface $form,
        ZedUiFormResponseBuilderInterface $zedUiFormResponseBuilder
    ): ZedUiFormResponseBuilderInterface {
        $field = $form->get(MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS);

        /** @var array<\Symfony\Component\Form\FormError> $errors */
        $errors = $field->getErrors();
        foreach ($errors as $error) {
            $zedUiFormResponseBuilder->addErrorNotification($this->translatorFacade->trans($error->getMessage()));
        }

        return $zedUiFormResponseBuilder;
    }
}
