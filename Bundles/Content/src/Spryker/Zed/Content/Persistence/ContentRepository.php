<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Persistence;

use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Content\Persistence\ContentPersistenceFactory getFactory()
 */
class ContentRepository extends AbstractRepository implements ContentRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param int $id
     *
     * @return null|\Generated\Shared\Transfer\ContentTransfer
     */
    public function findContentById(int $id): ?ContentTransfer
    {
        $contentEntity = $this->getFactory()
            ->createContentQuery()
            ->findOneByIdContent($id);

        if ($contentEntity === null) {
            return null;
        }

        return $this->getFactory()->createContentMapper()->mapContentEntityToTransfer($contentEntity);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $uuid
     *
     * @return null|\Generated\Shared\Transfer\ContentTransfer
     */
    public function findContentByUUID(string $uuid): ?ContentTransfer
    {
        $contentEntity = $this
            ->getFactory()
            ->createContentQuery()
            ->findOneByUuid($uuid);

        if ($contentEntity === null) {
            return null;
        }

        return $this->getFactory()->createContentMapper()->mapContentEntityToTransfer($contentEntity);
    }
}
