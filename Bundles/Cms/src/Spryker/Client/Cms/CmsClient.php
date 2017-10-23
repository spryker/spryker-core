<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Cms\CmsFactory getFactory
 */
class CmsClient extends AbstractClient implements CmsClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use CMS Block module instead
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function findBlockByName(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->createCmsBlockFinder()->getBlockByName($cmsBlockTransfer);
    }

    /**
     * @deprecated Use CMS Block module instead
     *
     * @return \Spryker\Client\Cms\Storage\CmsBlockStorageInterface
     */
    private function createCmsBlockFinder()
    {
        return $this->getFactory()->createCmsBlockFinder();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer
     */
    public function getFlattenedLocaleCmsPageData(FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer)
    {
        return $this->getFactory()->createCmsStub()->getFlattenedLocaleCmsPageData($flattenedLocaleCmsPageDataRequestTransfer);
    }
}
