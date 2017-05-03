<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderTableTransfer;

class ProductApiToApiQueryBuilderBridge implements ProductApiToApiQueryBuilderInterface
{

    /**
     * @var \Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainerInterface
     */
    protected $apiQueryBuilderQueryContainer;

    /**
     * @param \Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainerInterface $apiQueryBuilderQueryContainer
     */
    public function __construct($apiQueryBuilderQueryContainer)
    {
        $this->apiQueryBuilderQueryContainer = $apiQueryBuilderQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $tableTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    public function toPropelQueryBuilderCriteria(
        ApiRequestTransfer $apiRequestTransfer,
        PropelQueryBuilderTableTransfer $tableTransfer
    ) {
        return $this->apiQueryBuilderQueryContainer->toPropelQueryBuilderCriteria($apiRequestTransfer, $tableTransfer);
    }

}
