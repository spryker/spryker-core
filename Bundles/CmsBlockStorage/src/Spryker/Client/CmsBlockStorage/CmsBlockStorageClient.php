<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsBlockStorage\CmsBlockStorageFactory getFactory()
 */
class CmsBlockStorageClient extends AbstractClient implements CmsBlockStorageClientInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $blockKey
     *
     * @return \Generated\Shared\Transfer\SpyCmsBlockTransfer
     */
    public function getBlockByKey($blockKey)
    {
        return $this->getFactory()
            ->createCmsBlockKeyValueStorage()
            ->getBlockByKey($blockKey);
    }

}
