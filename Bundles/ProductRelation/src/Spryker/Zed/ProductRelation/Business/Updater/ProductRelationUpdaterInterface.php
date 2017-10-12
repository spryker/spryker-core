<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Updater;

interface ProductRelationUpdaterInterface
{
    /**
     * @return void
     */
    public function rebuildRelations();
}
