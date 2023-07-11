<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Reader;

use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface;

class DynamicEntityReader implements DynamicEntityReaderInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface
     */
    protected DynamicEntityRepositoryInterface $repository;

    /**
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface $repository
     */
    public function __construct(DynamicEntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getEntityCollection(DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer): DynamicEntityCollectionTransfer
    {
        $dynamicEntityConfigurationTransfer = $this->repository->findDynamicEntityConfigurationByTableAlias(
            $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail(),
        );

        if ($dynamicEntityConfigurationTransfer === null) {
            return new DynamicEntityCollectionTransfer();
        }

        return $this->repository->getEntities(
            $dynamicEntityCriteriaTransfer,
            $dynamicEntityConfigurationTransfer,
        );
    }
}
