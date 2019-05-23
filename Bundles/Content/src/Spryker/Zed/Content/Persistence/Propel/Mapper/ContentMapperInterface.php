<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ContentTransfer;
use Orm\Zed\Content\Persistence\SpyContent;

interface ContentMapperInterface
{
    /**
     * @param \Orm\Zed\Content\Persistence\SpyContent $contentEntity
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function mapContentEntityToTransfer(SpyContent $contentEntity): ContentTransfer;
}
