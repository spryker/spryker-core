<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\MerchantStorageTransfer;
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

        $legalInformationTransfer = ($restMerchantsAttributesTransfer->getLegalInformation())
            ->setCancellationPolicy($merchantStorageProfileTransfer->getCancellationPolicyGlossaryKey())
            ->setDataPrivacy($merchantStorageProfileTransfer->getDataPrivacyGlossaryKey())
            ->setImprint($merchantStorageProfileTransfer->getImprintGlossaryKey())
            ->setTerms($merchantStorageProfileTransfer->getTermsConditionsGlossaryKey());

        return $restMerchantsAttributesTransfer->fromArray($merchantStorageProfileTransfer->toArray(), true)
            ->setBannerUrl($merchantStorageProfileTransfer->getBannerUrlGlossaryKey())
            ->setDescription($merchantStorageProfileTransfer->getDescriptionGlossaryKey())
            ->setDeliveryTime($merchantStorageProfileTransfer->getDeliveryTimeGlossaryKey())
            ->setMerchantPageUrl($merchantStorageProfileTransfer->getMerchantUrl())
            ->setLegalInformation($legalInformationTransfer)
            ->setMerchantName($merchantStorageTransfer->getName());
    }
}
