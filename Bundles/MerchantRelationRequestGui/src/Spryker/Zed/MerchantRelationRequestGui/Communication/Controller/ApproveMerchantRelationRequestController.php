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
class ApproveMerchantRelationRequestController extends AbstractStatusChangeController
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getForm(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): FormInterface
    {
        return $this->getFactory()->createApproveMerchantRelationRequestForm($merchantRelationRequestTransfer);
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
            'approveMerchantRelationRequestForm' => $statusChangeMerchantRelationRequestForm->createView(),
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
        return $statusChangeMerchantRelationRequestForm->getData();
    }
}
