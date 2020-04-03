<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestLegalInformationTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

class MerchantMapper implements MerchantMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapMerchantStorageTransferToRestMerchantAttributesTransfer(
        MerchantStorageTransfer $merchantStorageTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
    ): RestMerchantsAttributesTransfer {
        $merchantStorageProfileTransfer = $merchantStorageTransfer->getMerchantStorageProfile();

        $restLegalInformationTransfer = (new RestLegalInformationTransfer())
            ->setCancellationPolicy($merchantStorageProfileTransfer->getCancellationPolicyGlossaryKey())
            ->setDataPrivacy($merchantStorageProfileTransfer->getDataPrivacyGlossaryKey())
            ->setImprint($merchantStorageProfileTransfer->getImprintGlossaryKey())
            ->setTerms($merchantStorageProfileTransfer->getTermsConditionsGlossaryKey());

        return $restMerchantsAttributesTransfer->fromArray($merchantStorageTransfer->toArray(), true)
            ->fromArray($merchantStorageProfileTransfer->toArray(), true)
            ->setBannerUrl($merchantStorageProfileTransfer->getBannerUrlGlossaryKey())
            ->setDescription($merchantStorageProfileTransfer->getDescriptionGlossaryKey())
            ->setDeliveryTime($merchantStorageProfileTransfer->getDeliveryTimeGlossaryKey())
            ->setMerchantUrl($merchantStorageTransfer->getMerchantUrl())
            ->setLegalInformation($restLegalInformationTransfer)
            ->setMerchantName($merchantStorageTransfer->getName());
    }
}
