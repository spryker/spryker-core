<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;

class SharedCartsResourceMapper implements SharedCartsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShareDetailCollectionTransfer $shareDetailCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer[]
     */
    public function mapSharedCartsResource(ShareDetailCollectionTransfer $shareDetailCollectionTransfer): array
    {
        $sharedCartAttributesTransfers = [];
        foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
            $sharedCartAttributesTransfers[] = (new RestSharedCartsAttributesTransfer())
                ->setIdCompanyUser(
                    $shareDetailTransfer->getIdCompanyUser()
                )
                ->setIdCartPermissionGroup(
                    $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup()
                );
        }

        return $sharedCartAttributesTransfers;
    }
}
