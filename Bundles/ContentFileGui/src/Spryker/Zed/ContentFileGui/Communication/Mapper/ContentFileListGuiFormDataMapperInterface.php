<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Mapper;

use Generated\Shared\Transfer\ContentFileListTermTransfer;

interface ContentFileListGuiFormDataMapperInterface
{
    /**
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentFileListTermTransfer
     */
    public function mapDataToContentFileListTermTransfer(?array $params = null): ContentFileListTermTransfer;
}
