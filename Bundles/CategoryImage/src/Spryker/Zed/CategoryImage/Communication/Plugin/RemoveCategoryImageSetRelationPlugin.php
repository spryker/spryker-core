<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Communication\Plugin;

use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryImage\CategoryImageConfig getConfig()
 */
class RemoveCategoryImageSetRelationPlugin extends AbstractPlugin implements CategoryRelationDeletePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this->getFacade()
            ->deleteCategoryImageSetsByIdCategory($idCategory);
    }
}
