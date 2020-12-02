<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
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
        $categoryTransfer = $this->findCategory();
        if (!$categoryTransfer || !$categoryTransfer->getNodeCollection()) {
            return [];
        }

        $categoryOptionTree = [];
        foreach ($this->getCategoryChildNodeCollection($categoryTransfer)->getNodes() as $nodeTransfer) {
            $categoryOptionTree[] = (new OptionSelectGuiTableFilterTypeOptionsTransfer())
                ->setValue($nodeTransfer->getIdCategoryNode())
                ->setTitle($nodeTransfer->getCategory()->getLocalizedAttributes()->offsetGet(0)->getName())
                ->setChildren(new ArrayObject($this->getCategoryChildren($nodeTransfer->getChildrenNodes())));
        }

        return $categoryOptionTree;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer[]
     */
    protected function getCategoryChildren(NodeCollectionTransfer $nodeCollectionTransfer): array
    {
        $categoryOptionTree = [];
        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $categoryOptionTree[] = (new OptionSelectGuiTableFilterTypeOptionsTransfer())
                ->setValue($nodeTransfer->getIdCategoryNode())
                ->setTitle($nodeTransfer->getCategory()->getLocalizedAttributes()->offsetGet(0)->getName())
                ->setChildren(new ArrayObject($this->getCategoryChildren($nodeTransfer->getChildrenNodes())));
        }

        return $categoryOptionTree;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    protected function getCategoryChildNodeCollection(CategoryTransfer $categoryTransfer): NodeCollectionTransfer
    {
        $categoryNodeCollectionTransfer = $categoryTransfer->getNodeCollection();
        if (!$categoryNodeCollectionTransfer || $categoryNodeCollectionTransfer->getNodes()->count() === 0) {
            return new NodeCollectionTransfer();
        }

        return $categoryNodeCollectionTransfer->getNodes()->offsetGet(0)->getChildrenNodes() ?? new NodeCollectionTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    protected function findCategory(): CategoryTransfer
    {
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($this->productMerchantPortalGuiConfig->getMainCategoryIdForCategoryFilter())
            ->setLocaleName($this->localeFacade->getCurrentLocale()->getLocaleName())
            ->setWithChildrenRecursively(true);

        return $this->categoryFacade->findCategory($categoryCriteriaTransfer);
    }
}
