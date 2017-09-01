<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsContentWidget;

use Generated\Shared\Transfer\CmsPageCollectorDataTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsContentWidget\CmsContentWidgetFactory getFactory
 */
class CmsContentWidgetClient extends AbstractClient implements CmsContentWidgetClientInterface
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
        return $this->getFactory()->createCmsContentWidgetStub()->expandCmsPageCollectorData($cmsPageCollectorDataTransfer);
    }

}
