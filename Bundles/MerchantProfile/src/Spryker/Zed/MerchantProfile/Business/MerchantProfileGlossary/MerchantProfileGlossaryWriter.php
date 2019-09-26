<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface;

class MerchantProfileGlossaryWriter implements MerchantProfileGlossaryWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MerchantProfileToGlossaryFacadeInterface $glossaryFacade,
        MerchantProfileToLocaleFacadeInterface $localeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function saveMerchantProfileGlossaryAttributes(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        if ($merchantProfileTransfer->getMerchantProfileLocalizedGlossaryAttributes()) {
            $localeTransfers = $this->localeFacade->getLocaleCollection();

            foreach ($localeTransfers as $localeTransfer) {
                $this->saveMerchantProfileGlossaryLocalizedAttributesByProvidedLocale(
                    $localeTransfer,
                    $merchantProfileTransfer->getMerchantProfileLocalizedGlossaryAttributes(),
                    $merchantProfileTransfer
                );
            }
        }

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer[] $merchantProfileLocalizedGlossaryAttributesTransfers
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileGlossaryLocalizedAttributesByProvidedLocale(
        LocaleTransfer $localeTransfer,
        ArrayObject $merchantProfileLocalizedGlossaryAttributesTransfers,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        foreach ($merchantProfileLocalizedGlossaryAttributesTransfers as $merchantProfileLocalizedGlossaryAttributesTransfer) {
            if ($merchantProfileLocalizedGlossaryAttributesTransfer->getFkLocale() === $localeTransfer->getIdLocale()) {
                $merchantProfileTransfer = $this->saveMerchantProfileGlossaryAttributesByProvidedLocale(
                    $localeTransfer,
                    $merchantProfileLocalizedGlossaryAttributesTransfer->getMerchantProfileGlossaryAttributes(),
                    $merchantProfileTransfer
                );
            }
        }

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileGlossaryAttributesByProvidedLocale(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $merchantProfileTransfer = $this->saveMerchantProfileBannerUrlGlossary(
            $localeTransfer,
            $merchantProfileGlossaryAttributesTransfer,
            $merchantProfileTransfer
        );
        $merchantProfileTransfer = $this->saveMerchantProfileCancellationPolicyGlossary(
            $localeTransfer,
            $merchantProfileGlossaryAttributesTransfer,
            $merchantProfileTransfer
        );
        $merchantProfileTransfer = $this->saveMerchantProfileDataPrivacyGlossary(
            $localeTransfer,
            $merchantProfileGlossaryAttributesTransfer,
            $merchantProfileTransfer
        );
        $merchantProfileTransfer = $this->saveMerchantProfileDeliveryTimeGlossary(
            $localeTransfer,
            $merchantProfileGlossaryAttributesTransfer,
            $merchantProfileTransfer
        );
        $merchantProfileTransfer = $this->saveMerchantProfileDescriptionGlossary(
            $localeTransfer,
            $merchantProfileGlossaryAttributesTransfer,
            $merchantProfileTransfer
        );
        $merchantProfileTransfer = $this->saveMerchantProfileImprintGlossaryKey(
            $localeTransfer,
            $merchantProfileGlossaryAttributesTransfer,
            $merchantProfileTransfer
        );
        $merchantProfileTransfer = $this->saveMerchantProfileTermsConditionsGlossary(
            $localeTransfer,
            $merchantProfileGlossaryAttributesTransfer,
            $merchantProfileTransfer
        );

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileBannerUrlGlossary(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $bannerUrlGlossaryKey = $this->buildGlossaryKey($merchantProfileTransfer->getFkMerchant(), $merchantProfileTransfer::BANNER_URL_GLOSSARY_KEY);
        $this->saveGlossaryTranslation(
            $localeTransfer,
            $bannerUrlGlossaryKey,
            $merchantProfileGlossaryAttributesTransfer->getBannerUrlGlossary()
        );
        $merchantProfileTransfer->setBannerUrlGlossaryKey($bannerUrlGlossaryKey);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileCancellationPolicyGlossary(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $cancellationPolicyGlossaryKey = $this->buildGlossaryKey($merchantProfileTransfer->getFkMerchant(), $merchantProfileTransfer::CANCELLATION_POLICY_GLOSSARY_KEY);
        $this->saveGlossaryTranslation(
            $localeTransfer,
            $cancellationPolicyGlossaryKey,
            $merchantProfileGlossaryAttributesTransfer->getCancellationPolicyGlossary()
        );
        $merchantProfileTransfer->setCancellationPolicyGlossaryKey($cancellationPolicyGlossaryKey);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileDataPrivacyGlossary(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $dataPrivacyGlossaryKey = $this->buildGlossaryKey($merchantProfileTransfer->getFkMerchant(), $merchantProfileTransfer::DATA_PRIVACY_GLOSSARY_KEY);
        $this->saveGlossaryTranslation(
            $localeTransfer,
            $dataPrivacyGlossaryKey,
            $merchantProfileGlossaryAttributesTransfer->getDataPrivacyGlossary()
        );
        $merchantProfileTransfer->setDataPrivacyGlossaryKey($dataPrivacyGlossaryKey);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileDeliveryTimeGlossary(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $deliveryTimeGlossaryKey = $this->buildGlossaryKey($merchantProfileTransfer->getFkMerchant(), $merchantProfileTransfer::DELIVERY_TIME_GLOSSARY_KEY);
        $this->saveGlossaryTranslation(
            $localeTransfer,
            $deliveryTimeGlossaryKey,
            $merchantProfileGlossaryAttributesTransfer->getDeliveryTimeGlossary()
        );
        $merchantProfileTransfer->setDeliveryTimeGlossaryKey($deliveryTimeGlossaryKey);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileDescriptionGlossary(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $descriptionGlossaryKey = $this->buildGlossaryKey($merchantProfileTransfer->getFkMerchant(), $merchantProfileTransfer::DESCRIPTION_GLOSSARY_KEY);
        $this->saveGlossaryTranslation(
            $localeTransfer,
            $descriptionGlossaryKey,
            $merchantProfileGlossaryAttributesTransfer->getDescriptionGlossary()
        );
        $merchantProfileTransfer->setDescriptionGlossaryKey($descriptionGlossaryKey);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileImprintGlossaryKey(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $imprintGlossaryKey = $this->buildGlossaryKey($merchantProfileTransfer->getFkMerchant(), $merchantProfileTransfer::IMPRINT_GLOSSARY_KEY);
        $this->saveGlossaryTranslation(
            $localeTransfer,
            $imprintGlossaryKey,
            $merchantProfileGlossaryAttributesTransfer->getImprintGlossary()
        );
        $merchantProfileTransfer->setImprintGlossaryKey($imprintGlossaryKey);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileTermsConditionsGlossary(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributesTransfer $merchantProfileGlossaryAttributesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $termsConditionsGlossary = $this->buildGlossaryKey($merchantProfileTransfer->getFkMerchant(), $merchantProfileTransfer::TERMS_CONDITIONS_GLOSSARY_KEY);
        $this->saveGlossaryTranslation(
            $localeTransfer,
            $termsConditionsGlossary,
            $merchantProfileGlossaryAttributesTransfer->getTermsConditionsGlossary()
        );
        $merchantProfileTransfer->setTermsConditionsGlossaryKey($termsConditionsGlossary);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $merchantProfileGlossaryAttributeKey
     * @param string|null $value
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    protected function saveGlossaryTranslation(
        LocaleTransfer $localeTransfer,
        string $merchantProfileGlossaryAttributeKey,
        ?string $value
    ): TranslationTransfer {
        if ($this->glossaryFacade->hasKey($merchantProfileGlossaryAttributeKey)) {
            $this->glossaryFacade->createKey($merchantProfileGlossaryAttributeKey);
        }

        if ($this->glossaryFacade->hasTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer)) {
            return $this->glossaryFacade->updateTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer, $value);
        }

        return $this->glossaryFacade->createTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer, $value);
    }

    /**
     * @param int $fkMerchant
     * @param string $merchantProfileGlossaryAttributeName
     *
     * @return string
     */
    protected function buildGlossaryKey(int $fkMerchant, string $merchantProfileGlossaryAttributeName): string
    {
        return sprintf('merchantProfile.%s.fkMerchant.%s', $merchantProfileGlossaryAttributeName, $fkMerchant);
    }
}
