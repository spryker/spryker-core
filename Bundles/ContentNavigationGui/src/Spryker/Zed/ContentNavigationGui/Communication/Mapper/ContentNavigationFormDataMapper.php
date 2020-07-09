<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Mapper;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;

class ContentNavigationFormDataMapper implements ContentNavigationFormDataMapperInterface
{
    /**
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTermTransfer
     */
    public function mapFormDataToContentNavigationTermTransfer(?array $params = null): ContentNavigationTermTransfer
    {
        $contentNavigationTermTransfer = new ContentNavigationTermTransfer();

        if ($params) {
            $contentNavigationTermTransfer->fromArray($params);
        }

        return $contentNavigationTermTransfer;
    }
}
