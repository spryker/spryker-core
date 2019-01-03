<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ContentStorageTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Orm\Zed\Content\Persistence\SpyContent;
use Orm\Zed\ContentStorage\Persistence\SpyContentStorage;

interface ContentStorageMapperInterface
{
    /**
     * @param \Orm\Zed\Content\Persistence\SpyContent $contentEntity
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function mapContentEntityToTransfer(SpyContent $contentEntity, ContentTransfer $contentTransfer): ContentTransfer;

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     * @param \Orm\Zed\Content\Persistence\SpyContent $contentEntity
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    public function mapContentTransferToEntity(ContentTransfer $contentTransfer, SpyContent $contentEntity): SpyContent;

    /**
     * @param \Orm\Zed\ContentStorage\Persistence\SpyContentStorage $contentStorageEntity
     * @param \Generated\Shared\Transfer\ContentStorageTransfer $contentStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ContentStorageTransfer
     */
    public function mapContentStorageEntityToTransfer(SpyContentStorage $contentStorageEntity, ContentStorageTransfer $contentStorageTransfer): ContentStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\ContentStorageTransfer $contentStorageTransfer
     * @param \Orm\Zed\ContentStorage\Persistence\SpyContentStorage $contentStorageEntity
     *
     * @return \Orm\Zed\ContentStorage\Persistence\SpyContentStorage
     */
    public function mapContentStorageTransferToEntity(ContentStorageTransfer $contentStorageTransfer, SpyContentStorage $contentStorageEntity): SpyContentStorage;
}
