<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsCollector;

use Generated\Shared\Transfer\CmsPageCollectorDataTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsCollector\CmsCollectorFactory getFactory
 */
class CmsCollectorClient extends AbstractClient implements CmsCollectorClientInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageCollectorDataTransfer
     */
    public function expandCmsPageCollectorData(CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer)
    {
        return $this->getFactory()->createCmsCollectorStub()->expandCmsPageCollectorData($cmsPageCollectorDataTransfer);
    }

}
