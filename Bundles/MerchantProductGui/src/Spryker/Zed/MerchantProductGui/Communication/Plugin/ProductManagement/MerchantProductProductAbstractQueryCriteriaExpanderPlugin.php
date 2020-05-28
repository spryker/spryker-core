<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractQueryCriteriaExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductGui\Communication\MerchantProductGuiCommunicationFactory getFactory()
 */
class MerchantProductProductAbstractQueryCriteriaExpanderPlugin extends AbstractPlugin implements ProductAbstractQueryCriteriaExpanderPluginInterface
{
    protected const URL_PARAM_ID_MERCHANT = 'id-merchant';

    /**
     * {@inheritDoc}
     * - Expands QueryCriteriaTransfer with QueryJoinTransfer for filtering by idMerchant.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer
    {
        $idMerchant = $this->getFactory()
            ->getRequest()
            ->get(static::URL_PARAM_ID_MERCHANT);

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
