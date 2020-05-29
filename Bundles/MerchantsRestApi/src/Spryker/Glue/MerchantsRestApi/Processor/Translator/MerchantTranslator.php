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
        $glossaryStorageKeys = $this->getGlossaryStorageKeysFromMerchantStorageTransfers([$merchantStorageTransfer]);

        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        return $this->setTranslationsToMerchantStorageTransfers([$merchantStorageTransfer], $translations)[0]
            ?? $merchantStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function translateMerchantStorageTransfers(
        array $merchantStorageTransfers,
        string $localeName
    ): array {
        $glossaryStorageKeys = $this->getGlossaryStorageKeysFromMerchantStorageTransfers($merchantStorageTransfers);

        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        return $this->setTranslationsToMerchantStorageTransfers($merchantStorageTransfers, $translations);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     *
     * @return string[]
     */
    protected function getGlossaryStorageKeysFromMerchantStorageTransfers(array $merchantStorageTransfers): array
    {
        $glossaryKeys = [];

        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $merchantStorageTransferGlossaryKeys = [
                $merchantStorageTransfer->getMerchantProfile()->getBannerUrlGlossaryKey(),
                $merchantStorageTransfer->getMerchantProfile()->getCancellationPolicyGlossaryKey(),
                $merchantStorageTransfer->getMerchantProfile()->getDataPrivacyGlossaryKey(),
                $merchantStorageTransfer->getMerchantProfile()->getDeliveryTimeGlossaryKey(),
                $merchantStorageTransfer->getMerchantProfile()->getDescriptionGlossaryKey(),
                $merchantStorageTransfer->getMerchantProfile()->getImprintGlossaryKey(),
                $merchantStorageTransfer->getMerchantProfile()->getTermsConditionsGlossaryKey(),
            ];

            $glossaryKeys = array_merge($glossaryKeys, $merchantStorageTransferGlossaryKeys);
        }

        return array_unique(array_filter($glossaryKeys));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    protected function setTranslationsToMerchantStorageTransfers(array $merchantStorageTransfers, array $translations): array
    {
        $translatedMerchantStorageTransfers = [];

        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $merchantStorageProfileTransfer = $merchantStorageTransfer->getMerchantProfile();

            if (isset($translations[$merchantStorageProfileTransfer->getBannerUrlGlossaryKey()])) {
                $merchantStorageProfileTransfer->setBannerUrl($translations[$merchantStorageProfileTransfer->getBannerUrlGlossaryKey()]);
            }
            if (isset($translations[$merchantStorageProfileTransfer->getCancellationPolicyGlossaryKey()])) {
                $merchantStorageProfileTransfer->setCancellationPolicy($translations[$merchantStorageProfileTransfer->getCancellationPolicyGlossaryKey()]);
            }
            if (isset($translations[$merchantStorageProfileTransfer->getDataPrivacyGlossaryKey()])) {
                $merchantStorageProfileTransfer->setDataPrivacy($translations[$merchantStorageProfileTransfer->getDataPrivacyGlossaryKey()]);
            }
            if (isset($translations[$merchantStorageProfileTransfer->getDeliveryTimeGlossaryKey()])) {
                $merchantStorageProfileTransfer->setDeliveryTime($translations[$merchantStorageProfileTransfer->getDeliveryTimeGlossaryKey()]);
            }
            if (isset($translations[$merchantStorageProfileTransfer->getDescriptionGlossaryKey()])) {
                $merchantStorageProfileTransfer->setDescription($translations[$merchantStorageProfileTransfer->getDescriptionGlossaryKey()]);
            }
            if (isset($translations[$merchantStorageProfileTransfer->getImprintGlossaryKey()])) {
                $merchantStorageProfileTransfer->setImprint($translations[$merchantStorageProfileTransfer->getImprintGlossaryKey()]);
            }
            if (isset($translations[$merchantStorageProfileTransfer->getTermsConditionsGlossaryKey()])) {
                $merchantStorageProfileTransfer->setTermsConditions($translations[$merchantStorageProfileTransfer->getTermsConditionsGlossaryKey()]);
            }

            $translatedMerchantStorageTransfers[] = $merchantStorageTransfer->setMerchantProfile($merchantStorageProfileTransfer);
        }

        return $translatedMerchantStorageTransfers;
    }
}
