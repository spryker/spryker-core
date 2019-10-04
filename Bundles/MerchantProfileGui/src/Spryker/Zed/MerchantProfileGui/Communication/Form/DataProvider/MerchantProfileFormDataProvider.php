<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProfileGlossaryAttributeValuesTransfer;
use Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileFormType;
use Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig;

class MerchantProfileFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig $config
     * @param \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MerchantProfileGuiConfig $config,
        MerchantProfileGuiToGlossaryFacadeInterface $glossaryFacade,
        MerchantProfileGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->config = $config;
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
            MerchantProfileFormType::SALUTATION_CHOICES_OPTION => $this->config->getSalutationChoices(),
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
    public function getLocalizedUrlPrefix(LocaleTransfer $localeTransfer): string
    {
        $localeNameParts = explode('_', $localeTransfer->getLocaleName());
        $languageCode = $localeNameParts[0];
        $merchantUrlPrefix = $this->config->getMerchantUrlPrefix();

        if (empty($merchantUrlPrefix)) {
            return '/' . $languageCode . '/';
        }

        return '/' . $languageCode . '/' . $merchantUrlPrefix . '/';
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
        $merchantProfileLocalizedGlossaryAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());
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
        if ($this->glossaryFacade->hasTranslation($key, $localeTransfer)) {
            $translationTransfer = $this->glossaryFacade->getTranslation($key, $localeTransfer);

            return $translationTransfer->getValue();
        }

        return null;
    }
}
