<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiCategoryAttributesTransfer;
use Generated\Shared\Transfer\ApiCategoryImageTransfer;
use Generated\Shared\Transfer\ApiCategoryParentTransfer;
use Generated\Shared\Transfer\CategoryConditionsTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToLocaleFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToStoreFacadeInterface;

class CategoryMapper implements CategoryMapperInterface
{
    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToLocaleFacadeInterface
     */
    protected CategoriesBackendApiToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToStoreFacadeInterface
     */
    protected CategoriesBackendApiToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface
     */
    protected CategoriesBackendApiToCategoryFacadeInterface $categoryFacade;

    /**
     * @param \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(
        CategoriesBackendApiToLocaleFacadeInterface $localeFacade,
        CategoriesBackendApiToStoreFacadeInterface $storeFacade,
        CategoriesBackendApiToCategoryFacadeInterface $categoryFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->storeFacade = $storeFacade;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiCategoryAttributesTransfer $apiCategoryAttributesTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function mapApiCategoryAttributesTransferToCategoryTransfer(
        ApiCategoryAttributesTransfer $apiCategoryAttributesTransfer,
        CategoryTransfer $categoryTransfer
    ): CategoryTransfer {
        $categoryLocalizedAttributes = $categoryTransfer->getLocalizedAttributes();
        $categoryTransfer->fromArray($apiCategoryAttributesTransfer->modifiedToArray(), true);

        $categoryTransfer->setLocalizedAttributes(
            $this->mapApiCategoryLocalizedAttributesTransfersToCategoryLocalizedAttributesTransfers(
                $apiCategoryAttributesTransfer,
                $categoryLocalizedAttributes,
            ),
        );

        $categoryTransfer->setImageSets(
            $this->mapApiCategoryImageSetsToCategoryImageSets($apiCategoryAttributesTransfer->getImageSets(), new ArrayObject()),
        );

        $categoryTransfer->setStoreRelation(
            $this->mapStoreNamesToStoreRelationTransfer($apiCategoryAttributesTransfer->getStores()),
        );

        if ($apiCategoryAttributesTransfer->getParent()) {
            $categoryTransfer->setCategoryNode(
                $this->mapApiCategoryParentTransferToNodeTransfer(
                    $apiCategoryAttributesTransfer->getParentOrFail(),
                    $categoryTransfer,
                    $categoryTransfer->getCategoryNode() ?? new NodeTransfer(),
                ),
            );
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiCategoryAttributesTransfer $apiCategoryAttributesTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer> $categoryLocalizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer>
     */
    protected function mapApiCategoryLocalizedAttributesTransfersToCategoryLocalizedAttributesTransfers(
        ApiCategoryAttributesTransfer $apiCategoryAttributesTransfer,
        ArrayObject $categoryLocalizedAttributesTransfers
    ): ArrayObject {
        $localeTransfersMap = $this->localeFacade->getLocaleCollection();

        $localeNameToCategoryLocalizedAttributesTransferMap = [];
        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        foreach ($categoryLocalizedAttributesTransfers as $categoryLocalizedAttributesTransfer) {
            $localeName = $categoryLocalizedAttributesTransfer->getLocaleOrFail()->getLocaleNameOrFail();
            $localeNameToCategoryLocalizedAttributesTransferMap[$localeName] = $categoryLocalizedAttributesTransfer;
        }

        foreach ($apiCategoryAttributesTransfer->getLocalizedAttributes() as $apiLocalizedAttributeTransfer) {
            $localeName = $apiLocalizedAttributeTransfer->getLocaleOrFail();
            if (!array_key_exists($localeName, $localeTransfersMap)) {
                continue;
            }

            if (!array_key_exists($localeName, $localeNameToCategoryLocalizedAttributesTransferMap)) {
                $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
                $categoryLocalizedAttributesTransfers->append($categoryLocalizedAttributesTransfer);
                $localeNameToCategoryLocalizedAttributesTransferMap[$localeName] = $categoryLocalizedAttributesTransfer;
            }

            $categoryLocalizedAttributesTransfer = $localeNameToCategoryLocalizedAttributesTransferMap[$localeName];

            $categoryLocalizedAttributesTransfer->fromArray($apiLocalizedAttributeTransfer->modifiedToArray(), true);
            $categoryLocalizedAttributesTransfer->setLocale($localeTransfersMap[$localeName]);
        }

        return $categoryLocalizedAttributesTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiCategoryImageSetTransfer> $apiCategoryImageSets
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryImageSetTransfer> $categoryImageSets
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryImageSetTransfer>
     */
    protected function mapApiCategoryImageSetsToCategoryImageSets(ArrayObject $apiCategoryImageSets, ArrayObject $categoryImageSets): Arrayobject
    {
        $localeTransfersMap = $this->localeFacade->getLocaleCollection();
        foreach ($apiCategoryImageSets as $apiCategoryAttributesImageSetTransfer) {
            $imageSetTransfer = (new CategoryImageSetTransfer())->fromArray($apiCategoryAttributesImageSetTransfer->toArray(), true);

            $localeTransfer = $localeTransfersMap[$apiCategoryAttributesImageSetTransfer->getLocaleOrFail()];
            $imageSetTransfer->setLocale($localeTransfer);

            foreach ($apiCategoryAttributesImageSetTransfer->getImages() as $apiCategoryImageTransfer) {
                $imageSetTransfer->addCategoryImage($this->mapApiCategoryImageTransferToCategoryImageTransfer($apiCategoryImageTransfer));
            }

            $categoryImageSets->append($imageSetTransfer);
        }

        return $categoryImageSets;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiCategoryImageTransfer $apiCategoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    protected function mapApiCategoryImageTransferToCategoryImageTransfer(ApiCategoryImageTransfer $apiCategoryImageTransfer): CategoryImageTransfer
    {
        $categoryImageTransfer = (new CategoryImageTransfer())
            ->fromArray($apiCategoryImageTransfer->toArray(), true);

        $categoryImageTransfer->setExternalUrlSmall($apiCategoryImageTransfer->getSmallUrl());
        $categoryImageTransfer->setExternalUrlLarge($apiCategoryImageTransfer->getLargeUrl());

        return $categoryImageTransfer;
    }

    /**
     * @param array<string> $storeNames
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapStoreNamesToStoreRelationTransfer(array $storeNames): StoreRelationTransfer
    {
        $storeTransfers = $this->storeFacade->getStoreTransfersByStoreNames($storeNames);
        $storeRelationTransfer = new StoreRelationTransfer();

        foreach ($storeTransfers as $storeTransfer) {
            $storeRelationTransfer->addIdStores($storeTransfer->getIdStoreOrFail());
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiCategoryParentTransfer $apiCategoryParentTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function mapApiCategoryParentTransferToNodeTransfer(
        ApiCategoryParentTransfer $apiCategoryParentTransfer,
        CategoryTransfer $categoryTransfer,
        NodeTransfer $nodeTransfer
    ): NodeTransfer {
        $nodeTransfer->setNodeOrder($apiCategoryParentTransfer->getSortOrderOrFail());

        if ($apiCategoryParentTransfer->getCategoryKey() === null) {
            $categoryTransfer->setParentCategoryNode(null);
            $nodeTransfer->setIsRoot(true);

            return $nodeTransfer;
        }

        $parentCategoryTransfersCollection = $this->categoryFacade->getCategoryCollection(
            (new CategoryCriteriaTransfer())
                ->setCategoryConditions(
                    (new CategoryConditionsTransfer())
                        ->setCategoryKeys([
                            $apiCategoryParentTransfer->getCategoryKey(),
                        ])
                        ->setWithParentCategory(true),
                ),
        )->getCategories();

        /** @var \Generated\Shared\Transfer\CategoryTransfer $parentCategoryTransfer */
        $parentCategoryTransfer = $parentCategoryTransfersCollection->getIterator()->current();
        $categoryTransfer->setParentCategoryNode($parentCategoryTransfer->getCategoryNode());

        return $nodeTransfer;
    }
}
