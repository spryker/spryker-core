<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\CmsBlockStorage;

use Spryker\Zed\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageRelatedBlocksFinderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Communication\CmsBlockProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageFacadeInterface getFacade()
 */
class CmsBlockProductCmsBlockStorageRelatedBlocksFinderPlugin extends AbstractPlugin implements CmsBlockStorageRelatedBlocksFinderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function findRelatedCmsBlocks(array $options): array
    {
        return $this->getFactory()->createCmsBlockProductStorageReader()->findCmsBlocksByOptions($options);
    }
}
