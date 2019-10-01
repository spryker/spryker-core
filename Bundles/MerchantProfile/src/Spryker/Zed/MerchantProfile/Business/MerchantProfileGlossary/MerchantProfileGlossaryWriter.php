<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary;

use ArrayObject;
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
        if ($merchantProfileTransfer->getMerchantProfileLocalizedGlossaryAttributes() !== null) {
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
            if ((int)$merchantProfileLocalizedGlossaryAttributesTransfer->getFkLocale() === $localeTransfer->getIdLocale()) {
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
            } else {
                $this->saveGlossaryTranslation($localeTransfer, $merchantProfileGlossaryKey, $glossaryAttributeValue);
                $merchantProfileGlossaryKeys[$merchantProfileGlossaryAttributeFieldName] = $merchantProfileGlossaryKey;
            }
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
        return !empty($merchantProfileData[$merchantProfileGlossaryKeyFieldName])
            ? $merchantProfileData[$merchantProfileGlossaryKeyFieldName]
            : $this->merchantProfileGlossaryKeyBuilder->buildGlossaryKey($fkMerchant, $merchantProfileGlossaryKeyFieldName);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $merchantProfileGlossaryAttributeKey
     *
     * @return bool
     */
    protected function deleteGlossaryTranslation(LocaleTransfer $localeTransfer, string $merchantProfileGlossaryAttributeKey): bool
    {
        if ($this->glossaryFacade->hasTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer)) {
            return $this->glossaryFacade->deleteTranslation($merchantProfileGlossaryAttributeKey, $localeTransfer);
        }

        return false;
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
