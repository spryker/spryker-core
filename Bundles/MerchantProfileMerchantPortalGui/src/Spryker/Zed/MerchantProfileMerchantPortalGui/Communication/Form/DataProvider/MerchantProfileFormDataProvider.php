<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProfileGlossaryAttributeValuesTransfer;
use Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig;

class MerchantProfileFormDataProvider implements MerchantProfileFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig
     */
    protected $merchantProfileMerchantPortalGuiConfig;

    /**
     * @var \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig $merchantProfileMerchantPortalGuiConfig
     * @param \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MerchantProfileMerchantPortalGuiConfig $merchantProfileMerchantPortalGuiConfig,
        MerchantProfileMerchantPortalGuiToMerchantFacadeInterface $merchantFacade,
        MerchantProfileMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade,
        MerchantProfileMerchantPortalGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->merchantProfileMerchantPortalGuiConfig = $merchantProfileMerchantPortalGuiConfig;
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantById(int $idMerchant): ?MerchantTransfer
    {
        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
        $merchantCriteriaTransfer->setIdMerchant($idMerchant);

        $merchantTransfer = $this->merchantFacade->findOne($merchantCriteriaTransfer);

        if (!$merchantTransfer) {
            return null;
        }

        $merchantTransfer = $this->addMerchantProfileData($merchantTransfer);
        $merchantTransfer = $this->addInitialUrlCollection($merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function addMerchantProfileData(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantProfileTransfer = $merchantTransfer->getMerchantProfile() ?? new MerchantProfileTransfer();
        $merchantProfileTransfer = $this->addLocalizedGlossaryAttributes($merchantProfileTransfer);

        $merchantTransfer->setMerchantProfile($merchantProfileTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function addInitialUrlCollection(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantProfileUrlCollection = $merchantTransfer->getUrlCollection();
        $urlCollection = new ArrayObject();
        $availableLocaleTransfers = $this->localeFacade->getLocaleCollection();

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $urlCollection->append(
                $this->addUrlPrefixToUrlTransfer($merchantProfileUrlCollection, $localeTransfer)
            );
        }
        $merchantTransfer->setUrlCollection($urlCollection);

        return $merchantTransfer;
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\UrlTransfer> $merchantProfileUrlCollection
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\UrlTransfer[] $merchantProfileUrlCollection
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function addUrlPrefixToUrlTransfer(
        $merchantProfileUrlCollection,
        LocaleTransfer $localeTransfer
    ): UrlTransfer {
        $urlTransfer = new UrlTransfer();
        foreach ($merchantProfileUrlCollection as $urlTransfer) {
            if ($urlTransfer->getFkLocale() === $localeTransfer->getIdLocale()) {
                $urlTransfer->fromArray($urlTransfer->toArray(), true);

                break;
            }
        }
        $urlTransfer->setFkLocale($localeTransfer->getIdLocale());
        $urlTransfer->setUrlPrefix(
            $this->getLocalizedUrlPrefix($localeTransfer)
        );

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getLocalizedUrlPrefix(LocaleTransfer $localeTransfer): string
    {
        $localeName = $localeTransfer->getLocaleName();
        if (!$localeName) {
            return '';
        }

        $localeNameParts = explode('_', $localeName);
        $languageCode = $localeNameParts[0];

        return '/' . $languageCode . '/' . $this->merchantProfileMerchantPortalGuiConfig->getMerchantUrlPrefix() . '/';
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function addLocalizedGlossaryAttributes(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileGlossaryAttributeValues = new ArrayObject();
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        foreach ($localeTransfers as $localeTransfer) {
            $merchantProfileGlossaryAttributeValues->append(
                $this->addGlossaryAttributesByLocale($merchantProfileTransfer, $localeTransfer)
            );
        }

        $merchantProfileTransfer->setMerchantProfileLocalizedGlossaryAttributes($merchantProfileGlossaryAttributeValues);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer
     */
    protected function addGlossaryAttributesByLocale(
        MerchantProfileTransfer $merchantProfileTransfer,
        LocaleTransfer $localeTransfer
    ): MerchantProfileLocalizedGlossaryAttributesTransfer {
        $merchantProfileLocalizedGlossaryAttributesTransfer = new MerchantProfileLocalizedGlossaryAttributesTransfer();
        $merchantProfileLocalizedGlossaryAttributesTransfer->setLocale($localeTransfer);
        $merchantProfileLocalizedGlossaryAttributesTransfer->setMerchantProfileGlossaryAttributeValues(
            $this->addGlossaryAttributeTranslations($merchantProfileTransfer, $localeTransfer)
        );

        return $merchantProfileLocalizedGlossaryAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileGlossaryAttributeValuesTransfer
     */
    protected function addGlossaryAttributeTranslations(
        MerchantProfileTransfer $merchantProfileTransfer,
        LocaleTransfer $localeTransfer
    ): MerchantProfileGlossaryAttributeValuesTransfer {
        $merchantProfileGlossaryAttributeValuesTransfer = new MerchantProfileGlossaryAttributeValuesTransfer();

        $merchantProfileGlossaryAttributeValuesData = $merchantProfileGlossaryAttributeValuesTransfer->toArray(true, true);
        $merchantProfileData = $merchantProfileTransfer->toArray(true, true);
        foreach ($merchantProfileGlossaryAttributeValuesData as $merchantProfileGlossaryAttributeFieldName => $glossaryAttributeValue) {
            $merchantProfileGlossaryKey = $merchantProfileData[$merchantProfileGlossaryAttributeFieldName];
            if (empty($merchantProfileGlossaryKey)) {
                continue;
            }

            $merchantProfileGlossaryAttributeValuesData[$merchantProfileGlossaryAttributeFieldName] = $this->getLocalizedTranslationValue($merchantProfileGlossaryKey, $localeTransfer);
        }

        $merchantProfileGlossaryAttributeValuesTransfer->fromArray($merchantProfileGlossaryAttributeValuesData);

        return $merchantProfileGlossaryAttributeValuesTransfer;
    }

    /**
     * @param string $key
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function getLocalizedTranslationValue(string $key, LocaleTransfer $localeTransfer): ?string
    {
        if ($this->glossaryFacade->hasTranslation($key, $localeTransfer) === false) {
            return null;
        }

        $translationTransfer = $this->glossaryFacade->getTranslation($key, $localeTransfer);

        return $translationTransfer->getIsActive() ? $translationTransfer->getValue() : null;
    }
}
