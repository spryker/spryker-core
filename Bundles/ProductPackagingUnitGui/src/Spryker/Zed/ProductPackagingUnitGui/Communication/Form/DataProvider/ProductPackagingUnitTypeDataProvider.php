<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTranslationTransfer;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitFacadeInterface;

class ProductPackagingUnitTypeDataProvider implements ProductPackagingUnitTypeDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitFacadeInterface
     */
    protected $productPackagingUnitFacade;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitFacadeInterface $productPackagingUnitFacade
     * @param \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductPackagingUnitGuiToProductPackagingUnitFacadeInterface $productPackagingUnitFacade,
        ProductPackagingUnitGuiToLocaleFacadeInterface $localeFacade
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
        $availableNameLocalizations = $this->getAvailableNameLocalizations($productPackagingUnitTypeTransfer);

        foreach ($availableLocales as $localeTransfer) {
            if (!isset($availableNameLocalizations[$localeTransfer->getLocaleName()])) {
                $productPackagingUnitTypeTransfer
                    ->getTranslations()->append(
                        $this->createDefaultProductPackagingUnitTypeTranslationTransfer((string)$localeTransfer->getLocaleName())
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

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return array
     */
    protected function getAvailableNameLocalizations(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): array
    {
        $availableNameLocalizations = [];

        foreach ($productPackagingUnitTypeTransfer->getTranslations() as $translation) {
            $availableNameLocalizations[$translation->getLocaleCode()] = $translation->getLocaleCode();
        }

        return $availableNameLocalizations;
    }
}
