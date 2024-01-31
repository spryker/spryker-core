<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Business\Creator;

use Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\RawDynamicEntityTransfer;
use Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface;

class CategoryClosureTableCreator implements CategoryClosureTableCreatorInterface
{
    /**
     * @var \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig
     */
    protected CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig;

    /**
     * @var \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface
     */
    protected CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade;

    /**
     * @param \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig
     * @param \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(
        CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig,
        CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade
    ) {
        $this->categoryDynamicEntityConnectorConfig = $categoryDynamicEntityConnectorConfig;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function createCategoryClosureTableByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        $dynamicEntityPostEditResponseTransfer = new DynamicEntityPostEditResponseTransfer();
        if (!$this->isApplicable($dynamicEntityPostEditRequestTransfer)) {
            return $dynamicEntityPostEditResponseTransfer;
        }

        $categoryClosureTableCollectionRequestTransfer = $this->createCategoryClosureTableCollectionRequestTransfer(
            $dynamicEntityPostEditRequestTransfer,
        );

        $categoryClosureTableCollectionResponseTransfer = $this->categoryFacade->createCategoryClosureTableCollection(
            $categoryClosureTableCollectionRequestTransfer,
        );

        return $dynamicEntityPostEditResponseTransfer->setErrors(
            $categoryClosureTableCollectionResponseTransfer->getErrors(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return bool
     */
    protected function isApplicable(DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer): bool
    {
        return in_array(
            $dynamicEntityPostEditRequestTransfer->getTableNameOrFail(),
            $this->categoryDynamicEntityConnectorConfig->getCategoryClosureTableOnCreateByDynamicEntityApplicableTables(),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer
     */
    protected function createCategoryClosureTableCollectionRequestTransfer(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): CategoryClosureTableCollectionRequestTransfer {
        $categoryClosureTableCollectionRequestTransfer = (new CategoryClosureTableCollectionRequestTransfer())
            ->setIsTransactional(false);
        foreach ($dynamicEntityPostEditRequestTransfer->getRawDynamicEntities() as $rawDynamicEntity) {
            $categoryClosureTableCollectionRequestTransfer->addCategoryNode(
                $this->createNodeTransfer($rawDynamicEntity),
            );
        }

        return $categoryClosureTableCollectionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RawDynamicEntityTransfer $rawDynamicEntityTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function createNodeTransfer(RawDynamicEntityTransfer $rawDynamicEntityTransfer): NodeTransfer
    {
        return (new NodeTransfer())->fromArray($rawDynamicEntityTransfer->getFields(), true);
    }
}
