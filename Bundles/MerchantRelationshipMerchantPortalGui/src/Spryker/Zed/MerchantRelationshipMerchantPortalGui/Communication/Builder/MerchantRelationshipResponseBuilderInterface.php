<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder;

use ArrayObject;

interface MerchantRelationshipResponseBuilderInterface
{
    /**
     * @param array<string, mixed> $responseData
     * @param string $notificationMessage
     *
     * @return array<string, mixed>
     */
    public function addSuccessfulResponseDataToResponse(array $responseData, string $notificationMessage): array;

    /**
     * @param array<string, mixed> $responseData
     * @param string $notificationMessage
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationshipErrorTransfer>|null $merchantRelationshipErrorTransfers
     *
     * @return array<string, mixed>
     */
    public function addErrorResponseDataToResponse(
        array $responseData,
        string $notificationMessage,
        ?ArrayObject $merchantRelationshipErrorTransfers = null
    ): array;
}
