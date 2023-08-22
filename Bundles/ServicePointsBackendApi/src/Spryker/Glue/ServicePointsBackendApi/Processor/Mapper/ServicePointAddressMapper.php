<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\ServicePointAddressesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointAddressMapper implements ServicePointAddressMapperInterface
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
    ): ServicePointAddressesBackendApiAttributesTransfer {
        $servicePointAddressesBackendApiAttributesTransfer->fromArray($servicePointAddressTransfer->modifiedToArray(), true);
        $servicePointAddressesBackendApiAttributesTransfer->setCountryIso2Code($servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail());

        if ($servicePointAddressTransfer->getRegion()) {
            $servicePointAddressesBackendApiAttributesTransfer->setRegionUuid($servicePointAddressTransfer->getRegionOrFail()->getUuidOrFail());
        }

        return $servicePointAddressesBackendApiAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressesBackendApiAttributesTransfer $servicePointAddressesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function mapServicePointAddressesBackendApiAttributesTransferToServicePointAddressTransfer(
        ServicePointAddressesBackendApiAttributesTransfer $servicePointAddressesBackendApiAttributesTransfer,
        ServicePointAddressTransfer $servicePointAddressTransfer
    ): ServicePointAddressTransfer {
        $servicePointAddressTransfer->fromArray($servicePointAddressesBackendApiAttributesTransfer->modifiedToArray(), true);

        if ($servicePointAddressesBackendApiAttributesTransfer->isPropertyModified(ServicePointAddressesBackendApiAttributesTransfer::COUNTRY_ISO2_CODE)) {
            if (!$servicePointAddressTransfer->getCountry()) {
                $servicePointAddressTransfer->setCountry(new CountryTransfer());
            }

            $servicePointAddressTransfer->getCountryOrFail()->setIso2Code($servicePointAddressesBackendApiAttributesTransfer->getCountryIso2Code());
        }

        if ($servicePointAddressesBackendApiAttributesTransfer->isPropertyModified(ServicePointAddressesBackendApiAttributesTransfer::REGION_UUID)) {
            $regionTransfer = null;

            if ($servicePointAddressesBackendApiAttributesTransfer->getRegionUuid() !== null) {
                $regionTransfer = $servicePointAddressTransfer->getRegion() ?: new RegionTransfer();
                $regionTransfer->setUuid($servicePointAddressesBackendApiAttributesTransfer->getRegionUuidOrFail());
            }

            $servicePointAddressTransfer->setRegion($regionTransfer);
        }

        return $servicePointAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     * @param \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRelationshipTransfer
     */
    public function mapServicePointAddressTransferToGlueRelationshipTransfer(
        ServicePointAddressTransfer $servicePointAddressTransfer,
        GlueRelationshipTransfer $glueRelationshipTransfer
    ): GlueRelationshipTransfer {
        $servicePointAddressesBackendApiAttributesTransfer = $this->mapServicePointAddressTransferToServicePointAddressesBackendApiAttributesTransfer(
            $servicePointAddressTransfer,
            new ServicePointAddressesBackendApiAttributesTransfer(),
        );

        $glueResourceTransfer = (new GlueResourceTransfer())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINT_ADDRESSES)
            ->setId($servicePointAddressesBackendApiAttributesTransfer->getUuidOrFail())
            ->setAttributes($servicePointAddressesBackendApiAttributesTransfer);

        return $glueRelationshipTransfer->addResource($glueResourceTransfer);
    }
}
