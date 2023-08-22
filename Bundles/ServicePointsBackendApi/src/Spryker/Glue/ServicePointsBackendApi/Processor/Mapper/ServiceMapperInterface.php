<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\ServicesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServicesRequestBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServiceTransfer;

interface ServiceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\ServicesBackendApiAttributesTransfer $servicesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ServicesBackendApiAttributesTransfer
     */
    public function mapServiceTransferToServicesBackendApiAttributesTransfer(
        ServiceTransfer $serviceTransfer,
        ServicesBackendApiAttributesTransfer $servicesBackendApiAttributesTransfer
    ): ServicesBackendApiAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServicesRequestBackendApiAttributesTransfer $servicesRequestBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function mapServicesRequestBackendApiAttributesTransferToServiceTransfer(
        ServicesRequestBackendApiAttributesTransfer $servicesRequestBackendApiAttributesTransfer,
        ServiceTransfer $serviceTransfer
    ): ServiceTransfer;

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRelationshipTransfer
     */
    public function mapServiceTransferToGlueRelationshipTransfer(
        ServiceTransfer $serviceTransfer,
        GlueRelationshipTransfer $glueRelationshipTransfer
    ): GlueRelationshipTransfer;
}
