<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms;

use Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Cms\CmsFactory getFactory()
 */
class CmsClient extends AbstractClient implements CmsClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer
     */
    public function getFlattenedLocaleCmsPageData(FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer): FlattenedLocaleCmsPageDataRequestTransfer
    {
        return $this->getFactory()->createCmsStub()->getFlattenedLocaleCmsPageData($flattenedLocaleCmsPageDataRequestTransfer);
    }
}
