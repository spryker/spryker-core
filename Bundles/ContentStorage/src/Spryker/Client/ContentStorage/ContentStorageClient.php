<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

use Generated\Shared\Transfer\ContentQueryTransfer;
use Generated\Shared\Transfer\ExecutedContentStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentStorage\ContentStorageFactory getFactory()
 */
class ContentStorageClient extends AbstractClient implements ContentStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ExecutedContentStorageTransfer|null
     */
    public function findContentById(int $idContent, string $localeName): ?ExecutedContentStorageTransfer
    {
        return $this->getFactory()
            ->createContentStorage()
            ->findContentById($idContent, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentQueryTransfer|null
     */
    public function findContentQueryById(int $idContent, string $localeName): ?ContentQueryTransfer
    {
        return $this->getFactory()
            ->createContentStorage()
            ->findContentQueryById($idContent, $localeName);
    }
}
