<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentStorage\ContentStorageFactory getFactory()
 */
class ContentStorageClient extends AbstractClient implements ContentStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContextByKey(string $contentKey, string $localeName): ?ContentTypeContextTransfer
    {
        return $this->getFactory()
            ->createContentStorage()
            ->findContentTypeContextByKey($contentKey, $localeName);
    }
}
