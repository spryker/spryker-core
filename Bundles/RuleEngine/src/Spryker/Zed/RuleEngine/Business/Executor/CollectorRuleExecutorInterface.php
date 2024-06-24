<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Executor;

use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface CollectorRuleExecutorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(
        TransferInterface $collectableTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): array;
}
