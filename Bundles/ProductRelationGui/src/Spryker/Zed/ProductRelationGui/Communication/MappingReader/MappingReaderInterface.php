<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\MappingReader;

interface MappingReaderInterface
{
    /**
     * @return array|\Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer
     */
    public function getMappings();
}
