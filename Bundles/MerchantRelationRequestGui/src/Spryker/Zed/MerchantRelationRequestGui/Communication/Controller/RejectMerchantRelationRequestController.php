<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 */
class RejectMerchantRelationRequestController extends AbstractStatusChangeController
{
    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_REJECTED
     *
     * @var string
     */
    protected const STATUS_TO_SET = 'rejected';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_STATUS_CHANGED = 'Merchant relation request has been rejected.';

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getForm(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): FormInterface
    {
        return $this->getFactory()->createRejectMerchantRelationRequestForm();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Symfony\Component\Form\FormInterface $statusChangeMerchantRelationRequestForm
     *
     * @return array<string, mixed>
     */
    protected function getResponseData(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        FormInterface $statusChangeMerchantRelationRequestForm
    ): array {
        return [
            'rejectMerchantRelationRequestForm' => $statusChangeMerchantRelationRequestForm->createView(),
            'editMerchantRelationRequestUrl' => $this->getEditPageUrl($merchantRelationRequestTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Symfony\Component\Form\FormInterface $statusChangeMerchantRelationRequestForm
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    protected function getMerchantRelationRequestTransferToUpdate(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        FormInterface $statusChangeMerchantRelationRequestForm
    ): MerchantRelationRequestTransfer {
        return $merchantRelationRequestTransfer;
    }
}
