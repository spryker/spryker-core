<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Persistence\Propel\Builder;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface QueryBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): ModelCriteria;
}
