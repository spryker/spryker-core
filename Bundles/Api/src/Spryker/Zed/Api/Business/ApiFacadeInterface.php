<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\Routing\RouterInterface;

interface ApiFacadeInterface
{
    /**
     * Specification:
     * - Requires `ApiRequestTransfer.resource`, `ApiRequestTransfer.filter`, `ApiRequestTransfer.requestType` transfer properties to be set.
     * - Requires `ApiRequestTransfer.meta.resourceId` transfer property to be set if ApiRequestTransfer.meta` is set.
     * - Dispatches an API request.
     * - Throws {@link \Spryker\Zed\Api\Business\Exception\ApiDispatchingException} exception if action `ApiRequestTransfer.resourceAction` is not supported for resource `ApiRequestTransfer.resource`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function dispatch(ApiRequestTransfer $apiRequestTransfer);

    /**
     * Specification:
     * - Validates request data.
     * - Returns a non-empty array of validation errors in case errors occur.
     * - Executes {@link \Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer): array;

    /**
     * Specification:
     * - Applies configured ApiRequestTransferFilterAbstractPluginInterface plugins on ApiRequestTransfer,
     *   Returns filtered ApiRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function filterApiRequestTransfer(ApiRequestTransfer $apiRequestTransfer): ApiRequestTransfer;

    /**
     * Specification:
     * - Returns Router which handles Zed API calls.
     *
     * @api
     *
     * @internal
     *
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getApiRouter(): RouterInterface;

    /**
     * Specification:
     * - Creates the API collection response from the provided transfers.
     *
     * @api
     *
     * @param array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer> $transfers
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $transfers): ApiCollectionTransfer;

    /**
     * Specification:
     * - Creates the API item response from the provided transfer and id.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $transfer
     * @param string|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem(?AbstractTransfer $transfer = null, ?string $id = null): ApiItemTransfer;
}
