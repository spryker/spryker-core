<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\ServicePointAddressesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;

interface ServicePointAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     * @param \Generated\Shared\Transfer\ServicePointAddressesBackendApiAttributesTransfer $servicePointAddressesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressesBackendApiAttributesTransfer
     */
    public function mapServicePointAddressTransferToServicePointAddressesBackendApiAttributesTransfer(
        ServicePointAddressTransfer $servicePointAddressTransfer,
        ServicePointAddressesBackendApiAttributesTransfer $servicePointAddressesBackendApiAttributesTransfer
    ): ServicePointAddressesBackendApiAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressesBackendApiAttributesTransfer $servicePointAddressesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function mapServicePointAddressesBackendApiAttributesTransferToServicePointAddressTransfer(
        ServicePointAddressesBackendApiAttributesTransfer $servicePointAddressesBackendApiAttributesTransfer,
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
