<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\DataProvider;

use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category\CategoryReaderInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Product\ProductReaderInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface;

class ProductCategorySlotBlockDataProvider implements ProductCategorySlotBlockDataProviderInterface
{
    protected const KEY_OPTION_ALL_PRODUCTS = 'All Product Pages';
    protected const KEY_OPTION_SPECIFIC_PRODUCTS = 'Specific Product Pages';

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Product\ProductReaderInterface
     */
    protected $cmsSlotBlockProductCategoryGuiProductReader;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category\CategoryReaderInterface
     */
    protected $cmsSlotBlockProductCategoryGuiCategoryReader;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Product\ProductReaderInterface $cmsSlotBlockProductCategoryGuiProductReader
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category\CategoryReaderInterface $cmsSlotBlockProductCategoryGuiCategoryReader
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ProductReaderInterface $cmsSlotBlockProductCategoryGuiProductReader,
        CategoryReaderInterface $cmsSlotBlockProductCategoryGuiCategoryReader,
        CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->cmsSlotBlockProductCategoryGuiProductReader = $cmsSlotBlockProductCategoryGuiProductReader;
        $this->cmsSlotBlockProductCategoryGuiCategoryReader = $cmsSlotBlockProductCategoryGuiCategoryReader;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param int[]|null $productAbstractIds
     *
     * @return array
     */
    public function getOptions(?array $productAbstractIds = []): array
    {
        return [
            ProductCategorySlotBlockConditionForm::OPTION_ALL_ARRAY => $this->getAllOptions(),
            ProductCategorySlotBlockConditionForm::OPTION_PRODUCT_ARRAY => $this->cmsSlotBlockProductCategoryGuiProductReader
                ->getProductAbstracts($productAbstractIds),
            ProductCategorySlotBlockConditionForm::OPTION_CATEGORY_ARRAY => $this->cmsSlotBlockProductCategoryGuiCategoryReader
                ->getCategories(),
        ];
    }

    /**
     * @return array
     */
    protected function getAllOptions(): array
    {
        return [
            $this->translatorFacade->trans(static::KEY_OPTION_ALL_PRODUCTS) => true,
            $this->translatorFacade->trans(static::KEY_OPTION_SPECIFIC_PRODUCTS) => false,
        ];
    }
}
