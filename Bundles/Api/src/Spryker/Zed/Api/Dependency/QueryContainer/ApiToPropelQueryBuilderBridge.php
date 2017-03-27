<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Dependency\QueryContainer;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ApiToPropelQueryBuilderBridge implements ApiToPropelQueryBuilderInterface
{

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainer
     */
    protected $propelQueryBuilderQueryContainer;

    /**
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainer $propelQueryBuilderQueryContainer
     */
    public function __construct($propelQueryBuilderQueryContainer)
    {
        $this->propelQueryBuilderQueryContainer = $propelQueryBuilderQueryContainer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $criteriaTransfer)
    {
        return $this->propelQueryBuilderQueryContainer->createQuery($query, $criteriaTransfer);
    }

    /**
     * @param string $json
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    public function createPropelQueryBuilderCriteriaFromJson($json)
    {
        return $this->propelQueryBuilderQueryContainer->createPropelQueryBuilderCriteriaFromJson($json);
    }

}
