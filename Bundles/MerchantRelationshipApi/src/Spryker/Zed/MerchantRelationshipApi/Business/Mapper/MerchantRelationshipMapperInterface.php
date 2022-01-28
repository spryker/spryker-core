<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipApiTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

interface MerchantRelationshipMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    public function mapApiRequestTransferToMerchantRelationshipCriteriaTransfer(
        ApiRequestTransfer $apiRequestTransfer,
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function mapApiDataTransferToMerchantRelationshipTransfer(
        ApiDataTransfer $apiDataTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPaginationTransfer
     */
    public function mapPaginationTransferToApiPaginationTransfer(
        PaginationTransfer $paginationTransfer,
        ApiPaginationTransfer $apiPaginationTransfer
    ): ApiPaginationTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     * @param \Generated\Shared\Transfer\ApiCollectionTransfer $apiCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function mapMerchantRelationshipCollectionTransferToApiCollectionTransfer(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer,
        ApiCollectionTransfer $apiCollectionTransfer
    ): ApiCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipApiTransfer
     */
    public function mapMerchantRelationshipTransferToMerchantRelationshipApiTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
    ): MerchantRelationshipApiTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function mapMerchantRelationshipResponseTransferToApiValidationErrorTransfers(
        MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer,
        ArrayObject $apiValidationErrorTransfers
    ): ArrayObject;
}
