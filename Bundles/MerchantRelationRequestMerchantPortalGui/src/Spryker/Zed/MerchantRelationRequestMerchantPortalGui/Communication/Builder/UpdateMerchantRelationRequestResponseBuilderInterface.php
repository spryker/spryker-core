<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Symfony\Component\Form\FormInterface;

interface UpdateMerchantRelationRequestResponseBuilderInterface
{
    /**
     * @param array<string, mixed> $responseData
     *
     * @return array<string, mixed>
     */
    public function addSuccessResponseDataToResponse(array $responseData): array;

    /**
     * @param array<string, mixed> $responseData
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestForm
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer|null $merchantRelationRequestCollectionResponseTransfer
     *
     * @return array<string, mixed>
     */
    public function addErrorResponseDataToResponse(
        array $responseData,
        FormInterface $merchantRelationRequestForm,
        ?MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer = null
    ): array;
}
