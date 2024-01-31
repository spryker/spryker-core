<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Business\Reader;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryConditionsTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @var string
     */
    protected const FIELD_FK_CATEGORY = 'fk_category';

    /**
     * @var \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface
     */
    protected CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade;

    /**
     * @param \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryCollectionByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): CategoryCollectionTransfer {
        $categoryCriteriaTransfer = $this->createCategoryCriteriaTransfer(
            $this->extractCategoryIds($dynamicEntityPostEditRequestTransfer),
        );

        return $this->categoryFacade->getCategoryCollection($categoryCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return list<int>
     */
    protected function extractCategoryIds(DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer): array
    {
        $categoryIds = [];
        foreach ($dynamicEntityPostEditRequestTransfer->getRawDynamicEntities() as $rawDynamicEntity) {
            if (!isset($rawDynamicEntity->getFields()[static::FIELD_FK_CATEGORY])) {
                continue;
            }

            $categoryIds[] = $rawDynamicEntity->getFields()[static::FIELD_FK_CATEGORY];
        }

        return $categoryIds;
    }

    /**
     * @param list<int> $categoryIds
     *
     * @return \Generated\Shared\Transfer\CategoryCriteriaTransfer
     */
    protected function createCategoryCriteriaTransfer(array $categoryIds): CategoryCriteriaTransfer
    {
        $categoryConditionsTransfer = (new CategoryConditionsTransfer())
            ->setWithChildren(false)
            ->setWithChildrenRecursively(false)
            ->setWithNodes(false)
            ->setWithParentCategory(false)
            ->setCategoryIds($categoryIds);

        return (new CategoryCriteriaTransfer())->setCategoryConditions($categoryConditionsTransfer);
    }
}
