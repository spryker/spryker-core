<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface;

class ProductPackagingUnitTypeTranslationReader implements ProductPackagingUnitTypeTranslationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        ProductPackagingUnitToLocaleFacadeInterface $localeFacade,
        ProductPackagingUnitToGlossaryFacadeInterface $glossaryFacade
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
        foreach ($availableLocales as $localeTransfer) {
            $this->hydrateNameTranslations($productPackagingUnitTypeTransfer, $localeTransfer);
        }

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    protected function hydrateNameTranslations(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer,
        LocaleTransfer $localeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $nameTranslationKey = $productPackagingUnitTypeTransfer->getName();
        if (!$nameTranslationKey || !$this->glossaryFacade->hasTranslation($nameTranslationKey, $localeTransfer)) {
            return $productPackagingUnitTypeTransfer;
        }

        $translationTransfer = $this->glossaryFacade->getTranslation($nameTranslationKey, $localeTransfer);
        $productPackagingUnitTypeTransfer->addProductPackagingUnitTypeTranslation(
            (new ProductPackagingUnitTypeTranslationTransfer())
                ->setLocaleCode($localeTransfer->getLocaleName())
                ->setName($translationTransfer->getValue())
        );

        return $productPackagingUnitTypeTransfer;
    }
}
