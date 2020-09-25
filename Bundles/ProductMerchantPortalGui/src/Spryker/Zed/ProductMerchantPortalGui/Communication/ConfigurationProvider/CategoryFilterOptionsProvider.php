<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig;

class CategoryFilterOptionsProvider implements CategoryFilterOptionsProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig
     */
    protected $productMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig
     */
    public function __construct(
        ProductMerchantPortalGuiToCategoryFacadeInterface $categoryFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
        $this->productMerchantPortalGuiConfig = $productMerchantPortalGuiConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer[]
     */
    public function getCategoryFilterOptionsTree(): array
    {
        $categoryTree = $this->categoryFacade->getTreeNodeChildrenByIdCategoryAndLocale(
            $this->productMerchantPortalGuiConfig->getMainCategoryIdForCategoryFilter(),
            $this->localeFacade->getCurrentLocale()
        );

        $categoryOptionTree = [];

        foreach ($categoryTree as $category) {
            $categoryOptionTree[] = (new OptionSelectGuiTableFilterTypeOptionsTransfer())->setValue($category['id'])
                ->setTitle($category['text'])
                ->setChildren(new ArrayObject($this->getCategoryChildren($category['children'])));
        }

        return $categoryOptionTree;
    }

    /**
     * @phpstan-return array<OptionSelectGuiTableFilterTypeOptionsTransfer>
     *
     * @param mixed[] $categoryChildren
     *
     * @return \Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer[]
     */
    protected function getCategoryChildren(array $categoryChildren): array
    {
        $categoryOptionTree = [];
        foreach ($categoryChildren as $childCategory) {
            $categoryOptionTree[] = (new OptionSelectGuiTableFilterTypeOptionsTransfer())->setValue($childCategory['id'])
                ->setTitle($childCategory['text'])
                ->setChildren(new ArrayObject($this->getCategoryChildren($childCategory['children'])));
        }

        return $categoryOptionTree;
    }
}
