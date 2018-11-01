<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Rule\Query;

use Generated\Shared\Transfer\RuleQueryDataProviderTransfer;

interface QueryInterface
{
    /**
     * @return array
     */
    public function getMappings();

    /**
     * @param \Generated\Shared\Transfer\RuleQueryDataProviderTransfer|null $dataProviderTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|null
     */
    public function prepareQuery(?RuleQueryDataProviderTransfer $dataProviderTransfer = null);
}
