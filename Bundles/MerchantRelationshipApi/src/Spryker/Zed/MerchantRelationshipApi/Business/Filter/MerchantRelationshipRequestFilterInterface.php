<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Filter;

use Generated\Shared\Transfer\ApiDataTransfer;

interface MerchantRelationshipRequestFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     * @param array<string> $disallowedProperties
     *
     * @return \Generated\Shared\Transfer\ApiDataTransfer
     */
    public function filterOutDisallowedProperties(ApiDataTransfer $apiDataTransfer, array $disallowedProperties = []): ApiDataTransfer;
}
