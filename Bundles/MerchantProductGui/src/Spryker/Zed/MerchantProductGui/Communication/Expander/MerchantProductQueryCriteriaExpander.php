<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Expander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\Request;

class MerchantProductQueryCriteriaExpander implements MerchantProductQueryCriteriaExpanderInterface
{
    protected const URL_PARAM_ID_MERCHANT = 'id-merchant';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer
    {
        $idMerchant = $this->request->get(static::URL_PARAM_ID_MERCHANT);

        if (!$idMerchant) {
            return $queryCriteriaTransfer;
        }

        $queryCriteriaTransfer
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setJoinType(Criteria::INNER_JOIN)
                    ->setRelation('SpyMerchantProductAbstract')
                    ->setCondition(SpyMerchantProductAbstractTableMap::COL_FK_MERCHANT . sprintf(' = %d', $idMerchant))
            );

        return $queryCriteriaTransfer;
    }
}
