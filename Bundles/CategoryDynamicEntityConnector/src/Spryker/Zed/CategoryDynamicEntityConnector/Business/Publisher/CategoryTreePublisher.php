<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDynamicEntityConnector\Business\Publisher;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToEventFacadeInterface;

class CategoryTreePublisher implements CategoryTreePublisherInterface
{
    /**
     * @uses \Spryker\Shared\CategoryStorage\CategoryStorageConfig::CATEGORY_TREE_PUBLISH
     *
     * @var string
     */
    public const CATEGORY_TREE_PUBLISH = 'Category.tree.publish';

    /**
     * @var \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig
     */
    protected CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig;

    /**
     * @var \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToEventFacadeInterface
     */
    protected CategoryDynamicEntityConnectorToEventFacadeInterface $eventFacade;

    /**
     * @param \Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig
     * @param \Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToEventFacadeInterface $eventFacade
     */
    public function __construct(
        CategoryDynamicEntityConnectorConfig $categoryDynamicEntityConnectorConfig,
        CategoryDynamicEntityConnectorToEventFacadeInterface $eventFacade
    ) {
        $this->categoryDynamicEntityConnectorConfig = $categoryDynamicEntityConnectorConfig;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function publishCategoryTreeOnCreateByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        return $this->publishCategoryTreeByDynamicEntityRequest(
            $dynamicEntityPostEditRequestTransfer,
            $this->categoryDynamicEntityConnectorConfig->getCategoryTreePublishOnCreateByDynamicEntityApplicableTables(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function publishCategoryTreeOnUpdateByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        return $this->publishCategoryTreeByDynamicEntityRequest(
            $dynamicEntityPostEditRequestTransfer,
            $this->categoryDynamicEntityConnectorConfig->getCategoryTreePublishOnUpdateByDynamicEntityApplicableTables(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     * @param list<string> $applicableTableNames
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    protected function publishCategoryTreeByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer,
        array $applicableTableNames
    ): DynamicEntityPostEditResponseTransfer {
        $dynamicEntityPostEditResponseTransfer = new DynamicEntityPostEditResponseTransfer();
        if (!$this->isApplicable($dynamicEntityPostEditRequestTransfer, $applicableTableNames)) {
            return $dynamicEntityPostEditResponseTransfer;
        }

        $this->eventFacade->trigger(static::CATEGORY_TREE_PUBLISH, new NodeTransfer());

        return $dynamicEntityPostEditResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     * @param list<string> $applicableTableNames
     *
     * @return bool
     */
    protected function isApplicable(DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer, array $applicableTableNames): bool
    {
        return in_array(
            $dynamicEntityPostEditRequestTransfer->getTableNameOrFail(),
            $applicableTableNames,
            true,
        );
    }
}
