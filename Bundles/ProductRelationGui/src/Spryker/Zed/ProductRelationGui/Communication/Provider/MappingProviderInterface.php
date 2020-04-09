<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Provider;

interface MappingProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer[]
     */
    public function getMappings(): array;
}
