<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Communication\Plugin\MerchantRelationRequest;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationRequest\Communication\MerchantRelationRequestCommunicationFactory getFactory()
 */
class StatusChangeCompanyUserNotificationMerchantRelationshipRequestPostUpdatePlugin extends AbstractPlugin implements MerchantRelationRequestPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantRelationRequestCollectionResponse.merchantRelationRequests.uuid` to be set.
     * - Requires `MerchantRelationRequestCollectionResponse.merchantRelationRequests.merchant` to be set.
     * - Requires `MerchantRelationRequestCollectionResponse.merchantRelationRequests.companyUser` to be set.
     * - Requires `MerchantRelationRequestCollectionResponse.merchantRelationRequests.companyUser.customer` to be set.
     * - Sends a notification to the company user who initiated the request to the merchant that the request status has been changed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    public function postUpdate(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        $this->getFacade()->sendRequestStatusChangeMailNotification($merchantRelationRequestCollectionResponseTransfer);
    }
}
