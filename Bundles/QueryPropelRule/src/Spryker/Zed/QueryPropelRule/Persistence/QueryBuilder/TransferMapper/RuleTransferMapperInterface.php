<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\TransferMapper;

interface RuleTransferMapperInterface
{

    /**
     * @param string $json
     *
     * @return \Generated\Shared\Transfer\RuleQuerySetTransfer
     */
    public function createRuleQuerySetFromJson($json);

}
