<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface CustomerApiToApiInterface
{

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $criteriaTransfer);

    /**
     * @param string $json
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    public function createPropelQueryBuilderCriteriaFromJson($json);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapPagination(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer);

    /**
     * @param string $tableName
     * @param array $tableFields
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $allowedFields
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapFields($tableName, array $tableFields, ModelCriteria $query, array $allowedFields);

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $data);

    /**
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param int|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem($data, $id = null);

}
