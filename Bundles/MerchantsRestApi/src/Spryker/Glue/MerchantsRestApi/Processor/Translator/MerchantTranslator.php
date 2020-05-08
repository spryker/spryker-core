<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Translator;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface;

class MerchantTranslator implements MerchantTranslatorInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(MerchantsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function translateMerchantStorageTransfer(
        MerchantStorageTransfer $merchantStorageTransfer,
        string $localeName
    ): MerchantStorageTransfer {
        $glossaryStorageKeys = $this->getGlossaryStorageKeysFromMerchantStorageTransfer($merchantStorageTransfer);

        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        return $this->setTranslationsToMerchantStorageTransfer($merchantStorageTransfer, $translations);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return string[]
     */
    protected function getGlossaryStorageKeysFromMerchantStorageTransfer(MerchantStorageTransfer $merchantStorageTransfer): array
    {
        $glossaryKeys = [
            $merchantStorageTransfer->getMerchantProfile()->getBannerUrlGlossaryKey(),
            $merchantStorageTransfer->getMerchantProfile()->getCancellationPolicyGlossaryKey(),
            $merchantStorageTransfer->getMerchantProfile()->getDataPrivacyGlossaryKey(),
            $merchantStorageTransfer->getMerchantProfile()->getDeliveryTimeGlossaryKey(),
            $merchantStorageTransfer->getMerchantProfile()->getDescriptionGlossaryKey(),
            $merchantStorageTransfer->getMerchantProfile()->getImprintGlossaryKey(),
            $merchantStorageTransfer->getMerchantProfile()->getTermsConditionsGlossaryKey(),
        ];

        return array_unique(array_filter($glossaryKeys));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    protected function setTranslationsToMerchantStorageTransfer(MerchantStorageTransfer $merchantStorageTransfer, array $translations): MerchantStorageTransfer
    {
        $merchantStorageProfileTransfer = $merchantStorageTransfer->getMerchantProfile();

        if (isset($translations[$merchantStorageProfileTransfer->getBannerUrlGlossaryKey()])) {
            $merchantStorageProfileTransfer->setBannerUrlGlossaryKey($translations[$merchantStorageProfileTransfer->getBannerUrlGlossaryKey()]);
        }
        if (isset($translations[$merchantStorageProfileTransfer->getCancellationPolicyGlossaryKey()])) {
            $merchantStorageProfileTransfer->setCancellationPolicyGlossaryKey($translations[$merchantStorageProfileTransfer->getCancellationPolicyGlossaryKey()]);
        }
        if (isset($translations[$merchantStorageProfileTransfer->getDataPrivacyGlossaryKey()])) {
            $merchantStorageProfileTransfer->setDataPrivacyGlossaryKey($translations[$merchantStorageProfileTransfer->getDataPrivacyGlossaryKey()]);
        }
        if (isset($translations[$merchantStorageProfileTransfer->getDeliveryTimeGlossaryKey()])) {
            $merchantStorageProfileTransfer->setDeliveryTimeGlossaryKey($translations[$merchantStorageProfileTransfer->getDeliveryTimeGlossaryKey()]);
        }
        if (isset($translations[$merchantStorageProfileTransfer->getDescriptionGlossaryKey()])) {
            $merchantStorageProfileTransfer->setDescriptionGlossaryKey($translations[$merchantStorageProfileTransfer->getDescriptionGlossaryKey()]);
        }
        if (isset($translations[$merchantStorageProfileTransfer->getImprintGlossaryKey()])) {
            $merchantStorageProfileTransfer->setImprintGlossaryKey($translations[$merchantStorageProfileTransfer->getImprintGlossaryKey()]);
        }
        if (isset($translations[$merchantStorageProfileTransfer->getTermsConditionsGlossaryKey()])) {
            $merchantStorageProfileTransfer->setTermsConditionsGlossaryKey($translations[$merchantStorageProfileTransfer->getTermsConditionsGlossaryKey()]);
        }

        return $merchantStorageTransfer->setMerchantProfile($merchantStorageProfileTransfer);
    }
}
