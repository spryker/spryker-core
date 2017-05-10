<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiQueryBuilder\Dependency\QueryContainer;

class ApiQueryBuilderToApiBridge implements ApiQueryBuilderToApiInterface
{

    /**
     * @var \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface $apiQueryContainer
     */
    public function __construct($apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $data)
    {
        return $this->apiQueryContainer->createApiCollection($data);
    }

    /**
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem($data)
    {
        return $this->apiQueryContainer->createApiItem($data);
    }

}
