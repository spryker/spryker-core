<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form\ConfigurationProvider;

use Generated\Shared\Transfer\MerchantRelationRequestFormActionConfigurationTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form\MerchantRelationRequestForm;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig;

class MerchantRelationRequestFormActionConfigurationProvider implements MerchantRelationRequestFormActionConfigurationProviderInterface
{
    /**
     * @var string
     */
    protected const BUTTON_VARIANT_REJECT = 'critical-outline';

    /**
     * @var string
     */
    protected const BUTTON_VARIANT_APPROVE = 'primary';

    /**
     * @var string
     */
    protected const MODAL_TITLE_REJECT = 'Reject Request';

    /**
     * @var string
     */
    protected const MODAL_TITLE_APPROVE = 'Approve Request';

    /**
     * @var string
     */
    protected const MODAL_BODY_REJECT = 'Please confirm if you want to proceed with rejecting the merchant relation request.';

    /**
     * @var string
     */
    protected const MODAL_BODY_APPROVE = 'Please confirm if you want to proceed with approving the merchant relation request.';

    /**
     * @var string
     */
    protected const MODAL_CANCEL_TEXT_REJECT = 'Cancel';

    /**
     * @var string
     */
    protected const MODAL_CANCEL_TEXT_APPROVE = 'Cancel';

    /**
     * @var string
     */
    protected const MODAL_CANCEL_VARIANT_REJECT = 'secondary';

    /**
     * @var string
     */
    protected const MODAL_CANCEL_VARIANT_APPROVE = 'secondary';

    /**
     * @var string
     */
    protected const MODAL_CONFIRM_TEXT_REJECT = 'Confirm reject';

    /**
     * @var string
     */
    protected const MODAL_CONFIRM_TEXT_APPROVE = 'Confirm approval ';

    /**
     * @var string
     */
    protected const MODAL_CONFIRM_VARIANT_REJECT = 'critical';

    /**
     * @var string
     */
    protected const MODAL_CONFIRM_VARIANT_APPROVE = 'primary';

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig
     */
    protected MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
     */
    public function __construct(
        MerchantRelationRequestMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
    ) {
        $this->translatorFacade = $translatorFacade;
        $this->merchantRelationRequestMerchantPortalGuiConfig = $merchantRelationRequestMerchantPortalGuiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return list<array<string>>
     */
    public function getActions(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): array
    {
        if ($this->isEditableMerchantRelationRequest($merchantRelationRequestTransfer->getStatusOrFail())) {
            return [
                $this->getRejectActionConfiguration(),
                $this->getApproveActionConfiguration(),
            ];
        }

        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRejectActionConfiguration(): array
    {
        return (new MerchantRelationRequestFormActionConfigurationTransfer())
            ->setName(MerchantRelationRequestForm::FIELD_REJECT)
            ->setLabel($this->translatorFacade->trans(MerchantRelationRequestForm::LABEL_REJECT))
            ->setButtonVariant(static::BUTTON_VARIANT_REJECT)
            ->setModalTitle($this->translatorFacade->trans(static::MODAL_TITLE_REJECT))
            ->setModalBody($this->translatorFacade->trans(static::MODAL_BODY_REJECT))
            ->setModalCancelText($this->translatorFacade->trans(static::MODAL_CANCEL_TEXT_REJECT))
            ->setModalCancelVariant(static::MODAL_CANCEL_VARIANT_REJECT)
            ->setModalConfirmText($this->translatorFacade->trans(static::MODAL_CONFIRM_TEXT_REJECT))
            ->setModalConfirmVariant(static::MODAL_CONFIRM_VARIANT_REJECT)
            ->toArray(true, true);
    }

    /**
     * @return array<string>
     */
    protected function getApproveActionConfiguration(): array
    {
        return (new MerchantRelationRequestFormActionConfigurationTransfer())
            ->setName(MerchantRelationRequestForm::FIELD_APPROVE)
            ->setLabel($this->translatorFacade->trans(MerchantRelationRequestForm::LABEL_APPROVE))
            ->setButtonVariant(static::BUTTON_VARIANT_APPROVE)
            ->setModalTitle($this->translatorFacade->trans(static::MODAL_TITLE_APPROVE))
            ->setModalBody($this->translatorFacade->trans(static::MODAL_BODY_APPROVE))
            ->setModalCancelText($this->translatorFacade->trans(static::MODAL_CANCEL_TEXT_APPROVE))
            ->setModalCancelVariant(static::MODAL_CANCEL_VARIANT_APPROVE)
            ->setModalConfirmText($this->translatorFacade->trans(static::MODAL_CONFIRM_TEXT_APPROVE))
            ->setModalConfirmVariant(static::MODAL_CONFIRM_VARIANT_APPROVE)
            ->toArray(true, true);
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isEditableMerchantRelationRequest(string $status): bool
    {
        return in_array($status, $this->merchantRelationRequestMerchantPortalGuiConfig->getEditableMerchantRelationRequestStatuses(), true);
    }
}
