<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Builder;

use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;

interface RuleSpecificationBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function build(RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer): RuleSpecificationInterface;
}
