<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Plugin;

use Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockViewPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductConnector\Communication\CmsBlockProductConnectorCommunicationFactory getFactory()
 */
class CmsBlockProductAbstractListViewPlugin extends AbstractPlugin implements CmsBlockViewPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return 'Product list';
    }

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
            ->getProductAbstractRenderedList($idCmsBlock, $idLocale);
    }
}
