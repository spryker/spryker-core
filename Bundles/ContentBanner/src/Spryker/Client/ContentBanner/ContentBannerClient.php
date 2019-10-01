<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentBanner\ContentBannerFactory getFactory()
 */
class ContentBannerClient extends AbstractClient implements ContentBannerClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer|null
     */
    public function executeBannerTypeByKey(string $contentKey, string $localeName): ?ContentBannerTypeTransfer
    {
        return $this->getFactory()->createContentBannerTypeMapper()->executeBannerTypeByKey($contentKey, $localeName);
    }
}
