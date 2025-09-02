<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\QueryBuilder;

use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;

class SspModelQueryBuilder
{
    public function applyCriteria(SpySspModelQuery $sspModelQuery, SspModelCriteriaTransfer $sspModelCriteriaTransfer): SpySspModelQuery
    {
        $sspModelQuery = $this->applyFilters($sspModelQuery, $sspModelCriteriaTransfer);
        $sspModelQuery = $this->addRelationsToQuery($sspModelQuery, $sspModelCriteriaTransfer);

        return $sspModelQuery;
    }

    protected function applyFilters(SpySspModelQuery $sspModelQuery, SspModelCriteriaTransfer $sspModelCriteriaTransfer): SpySspModelQuery
    {
        $sspModelConditionsTransfer = $sspModelCriteriaTransfer->getSspModelConditions();
        if (!$sspModelConditionsTransfer) {
            return $sspModelQuery;
        }

        if ($sspModelConditionsTransfer->getSspModelIds() !== []) {
            $sspModelQuery->filterByIdSspModel_In($sspModelConditionsTransfer->getSspModelIds());
        }

        if ($sspModelConditionsTransfer->getReferences() !== []) {
            $sspModelQuery->filterByReference_In($sspModelConditionsTransfer->getReferences());
        }

        return $sspModelQuery;
    }

    protected function addRelationsToQuery(SpySspModelQuery $sspModelQuery, SspModelCriteriaTransfer $sspModelCriteriaTransfer): SpySspModelQuery
    {
        if (!$sspModelCriteriaTransfer->getWithSspAssets()) {
            return $sspModelQuery;
        }

        $sspModelQuery
            ->leftJoinSpySspAssetToSspModel();

        return $sspModelQuery;
    }
}
