<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\TransferMapper;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use RuntimeException;
use Spryker\Zed\PropelQueryBuilder\Dependency\Service\PropelQueryBuilderToUtilEncodingInterface;

class RuleTransferMapper implements RuleTransferMapperInterface
{

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Dependency\Service\PropelQueryBuilderToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\PropelQueryBuilder\Dependency\Service\PropelQueryBuilderToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(PropelQueryBuilderToUtilEncodingInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $json
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    public function createRuleQuerySetFromJson($json)
    {
        $json = trim($json);

        $querySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        if ($json === '') {
            return $querySetTransfer;
        }

        $conditions = $this->utilEncodingService->decodeJson($json, true);
        if (!is_array($conditions)) {
            throw new RuntimeException('Invalid criteria JSON string.');
        }

        $querySetTransfer->fromArray($conditions);

        return $querySetTransfer;
    }

}
