<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ContentStorageTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use Orm\Zed\Content\Persistence\SpyContent;
use Orm\Zed\ContentStorage\Persistence\SpyContentStorage;

class ContentStorageMapper implements ContentStorageMapperInterface
{
    /**
     * @param \Orm\Zed\Content\Persistence\SpyContent $contentEntity
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function mapContentEntityToTransfer(SpyContent $contentEntity, ContentTransfer $contentTransfer): ContentTransfer
    {
        $contentTransfer->fromArray($contentEntity->toArray(), true);

        foreach ($contentEntity->getSpyContentLocalizeds() as $contentLocalizedEntity) {
            $localizedContentTransfer = new LocalizedContentTransfer();
            $localizedContentTransfer->fromArray($contentLocalizedEntity->toArray(), true);
            if ($contentLocalizedEntity->getFkLocale()) {
                $localizedContentTransfer->setLocaleName($contentLocalizedEntity->getSpyLocale()->getLocaleName());
            }

            $contentTransfer->addLocalizedContent($localizedContentTransfer);
        }

        return $contentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     * @param \Orm\Zed\Content\Persistence\SpyContent $contentEntity
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    public function mapContentTransferToEntity(ContentTransfer $contentTransfer, SpyContent $contentEntity): SpyContent
    {
        $contentEntity->fromArray($contentTransfer->toArray());

        return $contentEntity;
    }

    /**
     * @param \Orm\Zed\ContentStorage\Persistence\SpyContentStorage $contentStorageEntity
     * @param \Generated\Shared\Transfer\ContentStorageTransfer $contentStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ContentStorageTransfer
     */
    public function mapContentStorageEntityToTransfer(SpyContentStorage $contentStorageEntity, ContentStorageTransfer $contentStorageTransfer): ContentStorageTransfer
    {
        $contentStorageTransfer->fromArray($contentStorageEntity->toArray(), true);

        return $contentStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentStorageTransfer $contentStorageTransfer
     * @param \Orm\Zed\ContentStorage\Persistence\SpyContentStorage $contentStorageEntity
     *
     * @return \Orm\Zed\ContentStorage\Persistence\SpyContentStorage
     */
    public function mapContentStorageTransferToEntity(ContentStorageTransfer $contentStorageTransfer, SpyContentStorage $contentStorageEntity): SpyContentStorage
    {
        $contentStorageEntity->fromArray($contentStorageTransfer->toArray());

        return $contentStorageEntity;
    }
}
