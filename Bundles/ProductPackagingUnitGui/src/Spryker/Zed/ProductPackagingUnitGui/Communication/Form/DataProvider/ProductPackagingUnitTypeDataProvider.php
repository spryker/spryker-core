<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleBridge;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitInterface;

class ProductPackagingUnitTypeDataProvider
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitInterface
     */
    protected $productPackagingUnitFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleBridge
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitTypeGui\Dependency\Facade\ProductPackagingUnitTypeGuiToProductPackagingUnitTypeFacadeInterface $productPackagingUnitFacade
     * @param \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleBridge $localeFacade
     */
    public function __construct(
        ProductPackagingUnitGuiToProductPackagingUnitInterface $productPackagingUnitFacade,
        ProductPackagingUnitGuiToLocaleBridge $localeFacade
    ) {
        $this->productPackagingUnitFacade = $productPackagingUnitFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idProductPackagingUnitType
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getData(?int $idProductPackagingUnitType = null): ProductPackagingUnitTypeTransfer
    {
        $productPackagingUnitTypeTransfer = new ProductPackagingUnitTypeTransfer();

        if ($idProductPackagingUnitType !== null) {
            $productPackagingUnitTypeTransfer->setIdProductPackagingUnitType($idProductPackagingUnitType);
            $productPackagingUnitTypeTransfer = $this->productPackagingUnitFacade->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);
        }

        $this->addDefaultNameMissingTranslations($productPackagingUnitTypeTransfer);

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    protected function addDefaultNameMissingTranslations(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();
        $availableNameLocaleizations = [];
        foreach ($productPackagingUnitTypeTransfer->getNameTranslations() as $nameTranslation) {
            $availableNameLocaleizations[$nameTranslation->getLocaleCode()] = $nameTranslation->getLocaleCode();
        }

        foreach ($availableLocales as $localeTransfer) {
            if (!isset($availableNameLocaleizations[$localeTransfer->getLocaleName()])) {
                $productPackagingUnitTypeTransfer
                    ->getNameTranslations()->append(
                        $this->createDefaultProductPackagingUnitTypeTranslationTransfer($localeTransfer->getLocaleName())
                    );
            }
        }

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer
     */
    protected function createDefaultProductPackagingUnitTypeTranslationTransfer(string $localeName)
    {
        return (new ProductPackagingUnitTypeTranslationTransfer())
            ->setLocaleCode($localeName);
    }
}
