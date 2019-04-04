<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Executor;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;

interface BannerTypeExecutorInterface
{
    /**
     * @return string
     */
    public static function getTerm(): string;

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentBannerTypeTransfer;
}
