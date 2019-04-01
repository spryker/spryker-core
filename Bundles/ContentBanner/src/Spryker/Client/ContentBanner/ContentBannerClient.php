<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentBanner\ContentBannerFactory getFactory()
 */
class ContentBannerClient extends AbstractClient implements ContentBannerClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentBannerTermTransfer $contentBannerTermTransfer
     *
     * @return array
     */
    public function execute(ContentBannerTermTransfer $contentBannerTermTransfer): array
    {
        return $this->getFactory()
            ->createBannerTermExecutor()
            ->execute($contentBannerTermTransfer);
    }
}
