<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Business\Updater;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Spryker\Zed\CategoryDynamicEntityConnector\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface;

class CategoryUrlUpdater implements CategoryUrlUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig
     */
    protected CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig;

    /**
     * @var \Spryker\Zed\CategoryDynamicEntityConnector\Business\Reader\CategoryReaderInterface
     */
    protected CategoryReaderInterface $categoryReader;

    /**
     * @var \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface
     */
    protected CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade;

    /**
     * @param \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig
     * @param \Spryker\Zed\CategoryDynamicEntityConnector\Business\Reader\CategoryReaderInterface $categoryReader
     * @param \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(
        CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig,
        CategoryReaderInterface $categoryReader,
        CategoryDynamicEntityConnectorToCategoryFacadeInterface $categoryFacade
    ) {
        $this->categoryDynamicEntityConnectorConfig = $categoryDynamicEntityConnectorConfig;
        $this->categoryReader = $categoryReader;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function updateCategoryUrlByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        $dynamicEntityPostEditResponseTransfer = new DynamicEntityPostEditResponseTransfer();
        if (!$this->isApplicable($dynamicEntityPostEditRequestTransfer)) {
            return $dynamicEntityPostEditResponseTransfer;
        }

        $categoryCollectionTransfer = $this->categoryReader->getCategoryCollectionByDynamicEntityRequest(
            $dynamicEntityPostEditRequestTransfer,
        );

        $categoryUrlCollectionRequestTransfer = $this->createCategoryUrlCollectionRequestTransfer($categoryCollectionTransfer);
        if ($categoryUrlCollectionRequestTransfer->getCategories()->count() === 0) {
            return $dynamicEntityPostEditResponseTransfer;
        }

        $categoryUrlCollectionResponseTransfer = $this->categoryFacade->updateCategoryUrlCollection(
            $categoryUrlCollectionRequestTransfer,
        );

        return $dynamicEntityPostEditResponseTransfer->setErrors(
            $categoryUrlCollectionResponseTransfer->getErrors(),
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
            $this->categoryDynamicEntityConnectorConfig->getCategoryUrlOnUpdateByDynamicEntityApplicableTables(),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer
     */
    protected function createCategoryUrlCollectionRequestTransfer(
        CategoryCollectionTransfer $categoryCollectionTransfer
    ): CategoryUrlCollectionRequestTransfer {
        $categoryUrlCollectionRequestTransfer = (new CategoryUrlCollectionRequestTransfer())
            ->setIsTransactional(false);
        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            if (!$categoryTransfer->getCategoryNode() || $categoryTransfer->getLocalizedAttributes()->count() === 0) {
                continue;
            }

            $categoryUrlCollectionRequestTransfer->addCategory($categoryTransfer);
        }

        return $categoryUrlCollectionRequestTransfer;
    }
}
