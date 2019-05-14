<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper;

use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;

class SharedCartMapper implements SharedCartMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer
     */
    public function mapShareDetailTransferToRestSharedCartsAttributesTransfer(
        ShareDetailTransfer $shareDetailTransfer,
        RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
    ): RestSharedCartsAttributesTransfer {
        if (!$shareDetailTransfer->getCompanyUser() || !$shareDetailTransfer->getQuotePermissionGroup()) {
            return $restSharedCartsAttributesTransfer;
        }

        return $restSharedCartsAttributesTransfer
            ->fromArray($shareDetailTransfer->toArray(), true)
            ->setIdCompanyUser(
                $shareDetailTransfer->getCompanyUser()->getUuid()
            )
            ->setIdCartPermissionGroup(
                $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup()
            );
    }
}
