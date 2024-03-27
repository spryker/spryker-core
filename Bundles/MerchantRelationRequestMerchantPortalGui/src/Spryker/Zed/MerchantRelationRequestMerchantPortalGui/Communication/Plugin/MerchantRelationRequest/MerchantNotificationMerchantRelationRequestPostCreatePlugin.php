<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Plugin\MerchantRelationRequest;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\MerchantRelationRequestMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig getConfig()
 */
class MerchantNotificationMerchantRelationRequestPostCreatePlugin extends AbstractPlugin implements MerchantRelationRequestPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `MerchantRelationRequestCollectionResponseTransfer.merchantRelationRequests` to be provided.
     * - Requires `MerchantRelationRequestTransfer.merchant` to be set.
     * - Sends notification to merchant after merchant relation request is created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    public function postCreate(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        $this->getFactory()
            ->createMerchantNotificationSender()
            ->sentNotificationToMerchant($merchantRelationRequestCollectionResponseTransfer);
    }
}
