<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Plugin;

use Spryker\Zed\Category\Dependency\Plugin\CategoryDeleteRelationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacade getFacade()
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 */
class RemoveCmsBlockCategoryRelationPlugin extends AbstractPlugin implements CategoryDeleteRelationPluginInterface
{

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this
            ->getFacade()
            ->updateBlocksAssignedToDeletedCategoryNode($idCategory);
    }

}
