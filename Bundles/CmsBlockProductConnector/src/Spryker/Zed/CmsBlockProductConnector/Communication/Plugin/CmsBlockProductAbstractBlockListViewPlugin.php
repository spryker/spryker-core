<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagement\Communication\Plugin\ProductAbstractViewPluginInterface;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductConnector\Communication\CmsBlockProductConnectorCommunicationFactory getFactory()
 */
class CmsBlockProductAbstractBlockListViewPlugin extends AbstractPlugin implements ProductAbstractViewPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return 'CMS Block list';
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return string[]
     */
    public function getRenderedList($idProductAbstract)
    {
        return $this->getFacade()
            ->getCmsBlockRenderedList($idProductAbstract);
    }
}
