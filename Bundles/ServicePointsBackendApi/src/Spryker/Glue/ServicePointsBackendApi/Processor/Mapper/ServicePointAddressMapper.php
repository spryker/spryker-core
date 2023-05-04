<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointAddressMapper implements ServicePointAddressMapperInterface
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
    ): ApiServicePointAddressesAttributesTransfer {
        $apiServicePointAddressesAttributesTransfer->fromArray($servicePointAddressTransfer->modifiedToArray(), true);
        $apiServicePointAddressesAttributesTransfer->setCountryIso2Code($servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail());

        if ($servicePointAddressTransfer->getRegion()) {
            $apiServicePointAddressesAttributesTransfer->setRegionUuid($servicePointAddressTransfer->getRegionOrFail()->getUuidOrFail());
        }

        return $apiServicePointAddressesAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer $apiServicePointAddressesAttributesTransfer
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function mapApiServicePointAddressesAttributesTransferToServicePointAddressTransfer(
        ApiServicePointAddressesAttributesTransfer $apiServicePointAddressesAttributesTransfer,
        ServicePointAddressTransfer $servicePointAddressTransfer
    ): ServicePointAddressTransfer {
        $servicePointAddressTransfer->fromArray($apiServicePointAddressesAttributesTransfer->modifiedToArray(), true);

        if ($apiServicePointAddressesAttributesTransfer->isPropertyModified(ApiServicePointAddressesAttributesTransfer::COUNTRY_ISO2_CODE)) {
            if (!$servicePointAddressTransfer->getCountry()) {
                $servicePointAddressTransfer->setCountry(new CountryTransfer());
            }

            $servicePointAddressTransfer->getCountryOrFail()->setIso2Code($apiServicePointAddressesAttributesTransfer->getCountryIso2Code());
        }

        if ($apiServicePointAddressesAttributesTransfer->isPropertyModified(ApiServicePointAddressesAttributesTransfer::REGION_UUID)) {
            $regionTransfer = null;

            if ($apiServicePointAddressesAttributesTransfer->getRegionUuid() !== null) {
                $regionTransfer = $servicePointAddressTransfer->getRegion() ?: new RegionTransfer();
                $regionTransfer->setUuid($apiServicePointAddressesAttributesTransfer->getRegionUuidOrFail());
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
        $apiServicePointAddressesAttributesTransfer = $this->mapServicePointAddressTransferToApiServicePointAddressesAttributesTransfer(
            $servicePointAddressTransfer,
            new ApiServicePointAddressesAttributesTransfer(),
        );

        $glueResourceTransfer = (new GlueResourceTransfer())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINT_ADDRESSES)
            ->setId($apiServicePointAddressesAttributesTransfer->getUuidOrFail())
            ->setAttributes($apiServicePointAddressesAttributesTransfer);

        return $glueRelationshipTransfer->addResource($glueResourceTransfer);
    }
}
