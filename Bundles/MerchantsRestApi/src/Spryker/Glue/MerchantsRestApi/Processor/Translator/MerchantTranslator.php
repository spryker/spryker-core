<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Translator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
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
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function translateMerchantSearchCollection(
        MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer,
        string $localeName
    ): MerchantSearchCollectionTransfer {
        $glossarySearchKeys = $this->getGlossaryStorageKeysFromMerchantSearchCollection($merchantSearchCollectionTransfer);

        $translations = $this->glossaryStorageClient->translateBulk($glossarySearchKeys, $localeName);

        return $this->setTranslationsToMerchantSearchCollection($merchantSearchCollectionTransfer, $translations);
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
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return string[]
     */
    protected function getGlossaryStorageKeysFromMerchantSearchCollection(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): array
    {
        $glossaryKeys = [];

        foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
            $merchantSearchTransferGlossaryKeys = [
                $merchantSearchTransfer->getMerchantProfile()->getBannerUrlGlossaryKey(),
                $merchantSearchTransfer->getMerchantProfile()->getCancellationPolicyGlossaryKey(),
                $merchantSearchTransfer->getMerchantProfile()->getDataPrivacyGlossaryKey(),
                $merchantSearchTransfer->getMerchantProfile()->getDeliveryTimeGlossaryKey(),
                $merchantSearchTransfer->getMerchantProfile()->getDescriptionGlossaryKey(),
                $merchantSearchTransfer->getMerchantProfile()->getImprintGlossaryKey(),
                $merchantSearchTransfer->getMerchantProfile()->getTermsConditionsGlossaryKey(),
            ];

            $glossaryKeys = array_merge($glossaryKeys, $merchantSearchTransferGlossaryKeys);
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

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    protected function setTranslationsToMerchantSearchCollection(
        MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer,
        array $translations
    ): MerchantSearchCollectionTransfer {
        $translatedMerchantSearchTransfers = [];

        foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
            $merchantSearchProfileTransfer = $merchantSearchTransfer->getMerchantProfile();

            if (isset($translations[$merchantSearchProfileTransfer->getBannerUrlGlossaryKey()])) {
                $merchantSearchProfileTransfer->setBannerUrl($translations[$merchantSearchProfileTransfer->getBannerUrlGlossaryKey()]);
            }
            if (isset($translations[$merchantSearchProfileTransfer->getCancellationPolicyGlossaryKey()])) {
                $merchantSearchProfileTransfer->setCancellationPolicy($translations[$merchantSearchProfileTransfer->getCancellationPolicyGlossaryKey()]);
            }
            if (isset($translations[$merchantSearchProfileTransfer->getDataPrivacyGlossaryKey()])) {
                $merchantSearchProfileTransfer->setDataPrivacy($translations[$merchantSearchProfileTransfer->getDataPrivacyGlossaryKey()]);
            }
            if (isset($translations[$merchantSearchProfileTransfer->getDeliveryTimeGlossaryKey()])) {
                $merchantSearchProfileTransfer->setDeliveryTime($translations[$merchantSearchProfileTransfer->getDeliveryTimeGlossaryKey()]);
            }
            if (isset($translations[$merchantSearchProfileTransfer->getDescriptionGlossaryKey()])) {
                $merchantSearchProfileTransfer->setDescription($translations[$merchantSearchProfileTransfer->getDescriptionGlossaryKey()]);
            }
            if (isset($translations[$merchantSearchProfileTransfer->getImprintGlossaryKey()])) {
                $merchantSearchProfileTransfer->setImprint($translations[$merchantSearchProfileTransfer->getImprintGlossaryKey()]);
            }
            if (isset($translations[$merchantSearchProfileTransfer->getTermsConditionsGlossaryKey()])) {
                $merchantSearchProfileTransfer->setTermsConditions($translations[$merchantSearchProfileTransfer->getTermsConditionsGlossaryKey()]);
            }

            $translatedMerchantSearchTransfers[] = $merchantSearchTransfer->setMerchantProfile($merchantSearchProfileTransfer);
        }

        return $merchantSearchCollectionTransfer->setMerchants(new ArrayObject($translatedMerchantSearchTransfers));
    }
}
