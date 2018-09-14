<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilTextServiceInterface;

class ProductPackagingUnitTypeKeyGenerator implements ProductPackagingUnitTypeKeyGeneratorInterface
{
    protected const PRODUCT_PACKAGING_UNIT_TYPE_KEY = 'packaging_unit_type.%s.name';

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        ProductPackagingUnitToUtilTextServiceInterface $utilTextService
    ) {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return void
     */
    public function generateProductPackagingUnitTypeKey(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): void
    {
        if ($productPackagingUnitTypeTransfer->getName()) {
            return;
        }

        $name = $this->generateTranslationName($productPackagingUnitTypeTransfer);
        $productPackagingUnitTypeTransfer->setName($name);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return string
     */
    protected function generateTranslationName(ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer): string
    {
        $translations = $productPackagingUnitTypeTransfer->getTranslations();
        /** @var \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $enTranslation */
        $enTranslation = reset($translations);

        return sprintf(
            static::PRODUCT_PACKAGING_UNIT_TYPE_KEY,
            $this->utilTextService->generateSlug($enTranslation->getName())
        );
    }
}
