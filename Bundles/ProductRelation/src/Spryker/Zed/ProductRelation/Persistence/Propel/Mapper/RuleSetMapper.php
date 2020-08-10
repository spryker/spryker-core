<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;

class RuleSetMapper
{
    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(ProductRelationToUtilEncodingInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $querySetData
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $propelQueryBuilderRuleSetTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    public function mapQuerySetDataToPropelQueryBuilderRuleSetTransfer(
        string $querySetData,
        PropelQueryBuilderRuleSetTransfer $propelQueryBuilderRuleSetTransfer
    ): PropelQueryBuilderRuleSetTransfer {
        if ($querySetData) {
            $propelQueryBuilderRuleSetTransfer->fromArray(
                $this->utilEncodingService->decodeJson($querySetData, true),
                true
            );
        }

        return $propelQueryBuilderRuleSetTransfer;
    }
}
