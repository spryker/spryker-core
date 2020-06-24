<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCmsPagesAttributesTransfer;

class CmsPageMapper implements CmsPageMapperInterface
{
    /**
     * @phpstan-return array<string, \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer>
     *
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer[] $cmsPageStorageTransfers
     *
     * @return \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer[]
     */
    public function mapCmsPageStorageTransfersToRestCmsPagesAttributesTransfers(array $cmsPageStorageTransfers): array
    {
        $restCmsPagesAttributesTransfers = [];
        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
            $restCmsPagesAttributesTransfers[$cmsPageStorageTransfer->getUuid()] = (new RestCmsPagesAttributesTransfer())
                ->fromArray($cmsPageStorageTransfer->toArray(), true);
        }

        return $restCmsPagesAttributesTransfers;
    }
}
