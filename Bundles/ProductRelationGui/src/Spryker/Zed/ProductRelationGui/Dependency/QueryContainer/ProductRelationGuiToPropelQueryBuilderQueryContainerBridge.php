<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Dependency\QueryContainer;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ProductRelationGuiToPropelQueryBuilderQueryContainerBridge implements ProductRelationGuiToPropelQueryBuilderQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainerInterface
     */
    protected $propelQueryBuilderQueryContainer;

    /**
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainerInterface $propelQueryBuilderQueryContainer
     */
    public function __construct($propelQueryBuilderQueryContainer)
    {
        $this->propelQueryBuilderQueryContainer = $propelQueryBuilderQueryContainer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $criteriaTransfer)
    {
        return $this->propelQueryBuilderQueryContainer->createQuery($query, $criteriaTransfer);
    }
}
