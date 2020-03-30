<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper;

use Generated\Shared\Transfer\LegalInformationTransfer;
use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer;

class MerchantOpeningHoursMapper implements MerchantOpeningHoursMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param \Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer $restMerchantOpeningHoursAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer
     */
    public function mapMerchantOpeningHoursStorageTransferToRestMerchantOpeningHoursAttributesTransfer(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        RestMerchantOpeningHoursAttributesTransfer $restMerchantOpeningHoursAttributesTransfer
    ): RestMerchantOpeningHoursAttributesTransfer {


//        $restDateScheduleTransfer = (new RestDateScheduleTransfer())->fromArray($merchantOpeningHoursStorageTransfer->getDateSchedule());
//        $restMerchantOpeningHoursAttributesTransfer->setRestDateSchedule()
        $merchantStorageProfileTransfer = $merchantStorageTransfer->getMerchantStorageProfile();

        $legalInformationTransfer = (new LegalInformationTransfer())
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
