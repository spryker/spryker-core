<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class RestErrorMessageCreator implements RestErrorMessageCreatorInterface
{
    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createServicePointNotProvidedErrorMessage(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_NOT_PROVIDED)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_NOT_PROVIDED);
    }

    /**
     * @param string $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createServicePointAddressMissingErrorMessage(string $servicePointUuid): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_ADDRESS_MISSING)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(
                sprintf(
                    ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_ADDRESS_MISSING,
                    $servicePointUuid,
                ),
            );
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createCustomerDataMissingErrorMessage(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING);
    }

    /**
     * @param list<string> $itemGroupKeys
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createItemShippingAddressMissingErrorMessage(array $itemGroupKeys): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_ITEM_SHIPPING_ADDRESS_MISSING)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(
                sprintf(
                    ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_ITEM_SHIPPING_ADDRESS_MISSING,
                    implode(', ', $itemGroupKeys),
                ),
            );
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createOnlyOneServicePointShouldBeSelectedErrorMessage(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_ONLY_ONE_SERVICE_POINT_SHOULD_BE_SELECTED)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_ONLY_ONE_SERVICE_POINT_SHOULD_BE_SELECTED);
    }
}
