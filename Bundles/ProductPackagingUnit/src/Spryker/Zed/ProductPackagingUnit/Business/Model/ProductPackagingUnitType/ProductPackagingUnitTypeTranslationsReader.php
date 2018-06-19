<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleInterface;

class ProductPackagingUnitTypeTranslationsReader implements ProductPackagingUnitTypeTranslationsReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryInterface $glossaryFacade
     */
    public function __construct(
        ProductPackagingUnitToLocaleInterface $localeFacade,
        ProductPackagingUnitToGlossaryInterface $glossaryFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function hydrateTranslations(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): ProductPackagingUnitTypeTransfer
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();
        $this->hydrateNameTranslations($productPackagingUnitTypeTransfer, $availableLocales);

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    protected function hydrateNameTranslations(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer, array $availableLocales): ProductPackagingUnitTypeTransfer
    {
        $productPackagingUnitTypeTransfer->requireName();
        $nameTranslationKey = $productPackagingUnitTypeTransfer->getName();
        foreach ($availableLocales as $localeTransfer) {
            if (!$this->glossaryFacade->hasTranslation($nameTranslationKey, $localeTransfer)) {
                continue;
            }

            $translationTransfer = $this->glossaryFacade->getTranslation($nameTranslationKey, $localeTransfer);
            $productPackagingUnitTypeTransfer->addProductPackagingUnitTypeNameTranslation(
                $this->createProductPackagingUnitTypeTranslationTransfer(
                    $translationTransfer->getValue(),
                    $localeTransfer->getLocaleName()
                )
            );
        }

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param string $translation
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer
     */
    protected function createProductPackagingUnitTypeTranslationTransfer(string $translation, string $localeCode): ProductPackagingUnitTypeTranslationTransfer
    {
        return (new ProductPackagingUnitTypeTranslationTransfer())
            ->setLocaleCode($localeCode)
            ->setTranslation($translation);
    }
}
