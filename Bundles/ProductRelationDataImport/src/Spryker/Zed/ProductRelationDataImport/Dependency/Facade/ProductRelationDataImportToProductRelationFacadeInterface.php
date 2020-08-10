<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Dependency\Facade;

interface ProductRelationDataImportToProductRelationFacadeInterface
{
    /**
     * @return void
     */
    public function rebuildRelations();
}
