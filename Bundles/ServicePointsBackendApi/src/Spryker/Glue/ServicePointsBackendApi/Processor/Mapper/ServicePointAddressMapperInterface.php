<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer;
use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;

interface ServicePointAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     * @param \Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer $apiServicePointAddressesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer
     */
    public function mapServicePointAddressTransferToApiServicePointAddressesAttributesTransfer(
        ServicePointAddressTransfer $servicePointAddressTransfer,
        ApiServicePointAddressesAttributesTransfer $apiServicePointAddressesAttributesTransfer
    ): ApiServicePointAddressesAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer $apiServicePointAddressesAttributesTransfer
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function mapApiServicePointAddressesAttributesTransferToServicePointAddressTransfer(
        ApiServicePointAddressesAttributesTransfer $apiServicePointAddressesAttributesTransfer,
        ServicePointAddressTransfer $servicePointAddressTransfer
    ): ServicePointAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     * @param \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRelationshipTransfer
     */
    public function mapServicePointAddressTransferToGlueRelationshipTransfer(
        ServicePointAddressTransfer $servicePointAddressTransfer,
        GlueRelationshipTransfer $glueRelationshipTransfer
    ): GlueRelationshipTransfer;
}
