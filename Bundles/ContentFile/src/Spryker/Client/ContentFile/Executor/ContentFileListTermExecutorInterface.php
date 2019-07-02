<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile\Executor;

use Generated\Shared\Transfer\ContentFileListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;

interface ContentFileListTermExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentFileListTypeTransfer;
}
