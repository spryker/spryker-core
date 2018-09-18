<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin;

use Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockViewPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Communication\CmsBlockCategoryConnectorCommunicationFactory getFactory()
 */
class CmsBlockCategoryListViewPlugin extends AbstractPlugin implements CmsBlockViewPluginInterface
{
    /**
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getRenderedList($idCmsBlock, $idLocale)
    {
        return $this->getFacade()
            ->getRenderedCategoryList($idCmsBlock, $idLocale);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return 'Category list';
    }
}
