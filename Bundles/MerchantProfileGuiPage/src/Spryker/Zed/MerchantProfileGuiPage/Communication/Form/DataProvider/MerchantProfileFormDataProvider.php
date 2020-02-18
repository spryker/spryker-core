<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProfileGlossaryAttributeValuesTransfer;
use Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\MerchantProfileFormType;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageConfig;

class MerchantProfileFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageConfig
     */
    protected $merchantProfileGuiPageConfig;

    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageConfig $merchantProfileGuiPageConfig
     * @param \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MerchantProfileGuiPageConfig $merchantProfileGuiPageConfig,
        MerchantProfileGuiPageToGlossaryFacadeInterface $glossaryFacade,
        MerchantProfileGuiPageToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantProfileGuiPageConfig = $merchantProfileGuiPageConfig;
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => MerchantProfileTransfer::class,
            'label' => false,
            MerchantProfileFormType::SALUTATION_CHOICES_OPTION => $this->merchantProfileGuiPageConfig->getSalutationChoices(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer|null $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function getData(?MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        if ($merchantProfileTransfer === null) {
            $merchantProfileTransfer = new MerchantProfileTransfer();
        }

        $merchantProfileTransfer = $this->addLocalizedGlossaryAttributes($merchantProfileTransfer);
        $merchantProfileTransfer = $this->addInitialUrlCollection($merchantProfileTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function addInitialUrlCollection(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileUrlCollection = $merchantProfileTransfer->getUrlCollection();
        $urlCollection = new ArrayObject();
        $availableLocaleTransfers = $this->localeFacade->getLocaleCollection();

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $urlCollection->append(
                $this->addUrlPrefixToUrlTransfer($merchantProfileUrlCollection, $localeTransfer)
            );
        }
        $merchantProfileTransfer->setUrlCollection($urlCollection);

        return $merchantProfileTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\UrlTransfer[] $merchantProfileUrlCollection
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function addUrlPrefixToUrlTransfer($merchantProfileUrlCollection, LocaleTransfer $localeTransfer): UrlTransfer
    {
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
        $localeNameParts = explode('_', $localeTransfer->getLocaleName());
        $languageCode = $localeNameParts[0];

        return '/' . $languageCode . '/' . $this->merchantProfileGuiPageConfig->getMerchantUrlPrefix() . '/';
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
