<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProfileGlossaryAttributeValuesTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\MerchantProfile\Business\GlossaryKeyBuilder\MerchantProfileGlossaryKeyBuilderInterface;
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
     * @var \Spryker\Zed\MerchantProfile\Business\GlossaryKeyBuilder\MerchantProfileGlossaryKeyBuilderInterface
     */
    protected $merchantProfileGlossaryKeyBuilder;

    /**
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\MerchantProfile\Business\GlossaryKeyBuilder\MerchantProfileGlossaryKeyBuilderInterface $merchantProfileGlossaryKeyBuilder
     */
    public function __construct(
        MerchantProfileToGlossaryFacadeInterface $glossaryFacade,
        MerchantProfileToLocaleFacadeInterface $localeFacade,
        MerchantProfileGlossaryKeyBuilderInterface $merchantProfileGlossaryKeyBuilder
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
        $this->merchantProfileGlossaryKeyBuilder = $merchantProfileGlossaryKeyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function saveMerchantProfileGlossaryAttributes(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        if (count($merchantProfileTransfer->getMerchantProfileLocalizedGlossaryAttributes()) === 0) {
            return $merchantProfileTransfer;
        }

        $localeTransfers = $this->localeFacade->getLocaleCollection();
        foreach ($localeTransfers as $localeTransfer) {
            $this->saveMerchantProfileGlossaryLocalizedAttributes(
                $localeTransfer,
                $merchantProfileTransfer
            );
        }

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileGlossaryLocalizedAttributes(
        LocaleTransfer $localeTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        foreach ($merchantProfileTransfer->getMerchantProfileLocalizedGlossaryAttributes() as $merchantProfileLocalizedGlossaryAttributesTransfer) {
            if ($merchantProfileLocalizedGlossaryAttributesTransfer->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                $merchantProfileTransfer = $this->saveMerchantProfileGlossaryAttributesByProvidedLocale(
                    $localeTransfer,
                    $merchantProfileLocalizedGlossaryAttributesTransfer->getMerchantProfileGlossaryAttributeValues(),
                    $merchantProfileTransfer
                );
            }
        }

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileGlossaryAttributeValuesTransfer $merchantProfileGlossaryAttributeValuesTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileGlossaryAttributesByProvidedLocale(
        LocaleTransfer $localeTransfer,
        MerchantProfileGlossaryAttributeValuesTransfer $merchantProfileGlossaryAttributeValuesTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        $merchantProfileGlossaryAttributeValuesData = $merchantProfileGlossaryAttributeValuesTransfer->toArray(true, true);
        $merchantProfileData = $merchantProfileTransfer->toArray(true, true);

        $merchantProfileGlossaryKeys = [];
        foreach ($merchantProfileGlossaryAttributeValuesData as $merchantProfileGlossaryAttributeFieldName => $glossaryAttributeValue) {
            $merchantProfileGlossaryKey = $this->getMerchantProfileGlossaryAttributeKey(
                $merchantProfileData,
                $merchantProfileGlossaryAttributeFieldName,
                $merchantProfileTransfer->getFkMerchant()
            );

            if (empty($glossaryAttributeValue)) {
                $this->deleteGlossaryTranslation($localeTransfer, $merchantProfileGlossaryKey);
                continue;
            }

            $this->saveGlossaryTranslation($localeTransfer, $merchantProfileGlossaryKey, $glossaryAttributeValue);
            $merchantProfileGlossaryKeys[$merchantProfileGlossaryAttributeFieldName] = $merchantProfileGlossaryKey;
        }
        $merchantProfileTransfer->fromArray($merchantProfileGlossaryKeys);

        return $merchantProfileTransfer;
    }

    /**
     * @param array $merchantProfileData
     * @param string $merchantProfileGlossaryKeyFieldName
     * @param int $fkMerchant
     *
     * @return string
     */
    protected function getMerchantProfileGlossaryAttributeKey(
        array $merchantProfileData,
        string $merchantProfileGlossaryKeyFieldName,
        int $fkMerchant
    ): string {
        if (empty($merchantProfileData[$merchantProfileGlossaryKeyFieldName])) {
            return $this->merchantProfileGlossaryKeyBuilder->buildGlossaryKey($fkMerchant, $merchantProfileGlossaryKeyFieldName);
        }

        return $merchantProfileData[$merchantProfileGlossaryKeyFieldName];
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $merchantProfileGlossaryAttributeKey
     *
     * @return void
     */
    protected function deleteGlossaryTranslation(LocaleTransfer $localeTransfer, string $merchantProfileGlossaryAttributeKey): void
    {
        $this->glossaryFacade->deleteTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer);
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
        if ($value === null) {
            $value = '';
        }

        if (!$this->glossaryFacade->hasKey($merchantProfileGlossaryAttributeKey)) {
            $this->glossaryFacade->createKey($merchantProfileGlossaryAttributeKey);
        }

        if ($this->glossaryFacade->hasTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer)) {
            return $this->glossaryFacade->updateTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer, $value);
        }

        return $this->glossaryFacade->createTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer, $value);
    }
}
