<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface;
use Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface;

class CustomerApi
{

    /**
     * @var \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @var \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface $apiQueryContainer
     * @param \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface $queryContainer
     */
    public function __construct(
        CustomerApiToApiInterface $apiQueryContainer,
        CustomerApiQueryContainerInterface $queryContainer
    ) {
        $this->apiQueryContainer = $apiQueryContainer;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function get(ApiRequestTransfer $apiRequestTransfer)
    {
        return $apiRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function add(ApiRequestTransfer $apiRequestTransfer)
    {
        $customerTransfer = new CustomerTransfer();

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function update(ApiRequestTransfer $apiRequestTransfer)
    {
        $customerTransfer = new CustomerTransfer();

        return $apiRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return bool
     */
    public function delete(ApiRequestTransfer $apiRequestTransfer)
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer[]
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaRuleSet = new PropelQueryBuilderRuleSetTransfer();

        sd($apiRequestTransfer->toArray());
    }

}
