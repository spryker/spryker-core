<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Creator;

use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|\Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    public function create(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    );
}
