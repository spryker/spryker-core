<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Dependency\Facade;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class AvailabilityGuiToPropelQueryBuilderFacadeBridge implements AvailabilityGuiToPropelQueryBuilderFacadeInterface
{
    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Business\PropelQueryBuilderFacadeInterface
     */
    protected $propelQueryBuilderFacade;

    /**
     * @param \Spryker\Zed\PropelQueryBuilder\Business\PropelQueryBuilderFacadeInterface $propelQueryBuilderFacade
     */
    public function __construct($propelQueryBuilderFacade)
    {
        $this->propelQueryBuilderFacade = $propelQueryBuilderFacade;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(ModelCriteria $query, QueryCriteriaTransfer $queryCriteriaTransfer): ModelCriteria
    {
        return $this->propelQueryBuilderFacade->expandQuery($query, $queryCriteriaTransfer);
    }
}
