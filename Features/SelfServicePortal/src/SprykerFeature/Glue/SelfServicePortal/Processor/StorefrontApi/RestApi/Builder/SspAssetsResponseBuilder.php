<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder;

use ArrayObject;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestSspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\RestSspAssetsAttributesTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use SprykerFeature\Glue\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\HttpFoundation\Response;

class SspAssetsResponseBuilder implements SspAssetsResponseBuilderInterface
{
 /**
  * @var string
  */
    protected const GLOSSARY_KEY_ERROR_ASSET_NOT_FOUND = 'self_service_portal.asset.error.not-found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MESSAGE_ASSET_CREATION_ACCESS_DENIED = 'self_service_portal.asset.access.denied';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_ASSET_NAME = 'self_service_portal.asset.validation.name.not_set';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_UNKNOWN_ERROR = 'self_service_portal.asset.validation.unknown_error';

    /**
     * @var array<string, int>
     */
    protected const GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING = [
        self::GLOSSARY_KEY_ERROR_ASSET_NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::GLOSSARY_KEY_MESSAGE_ASSET_CREATION_ACCESS_DENIED => Response::HTTP_FORBIDDEN,
        self::GLOSSARY_KEY_INVALID_ASSET_NAME => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::GLOSSARY_KEY_UNKNOWN_ERROR => Response::HTTP_BAD_REQUEST,
    ];

    public function __construct(protected RestResourceBuilderInterface $restResourceBuilder, protected GlossaryStorageClientInterface $glossaryStorageClient)
    {
    }

    public function createSspAssetCollectionRestResponse(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $restSspAssetsAttributesTransfer = $this->mapSspAssetTransferToRestSspAssetsAttributesTransfer($sspAssetTransfer);
            $assetResource = $this->createSspAssetRestResource($restSspAssetsAttributesTransfer, $sspAssetTransfer->getReferenceOrFail());
            $restResponse->addResource($assetResource);
        }

        return $restResponse;
    }

    public function createSspAssetRestResponseFromSspAssetCollectionTransfer(
        SspAssetCollectionTransfer $assetCollectionTransfer,
        string $localeName
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $assetTransfers = $assetCollectionTransfer->getSspAssets();

        if ($assetTransfers->count() === 0) {
            return $this->createAssetNotFoundErrorResponse($localeName);
        }

        $sspAssetTransfer = $assetTransfers->getIterator()->current();

        $restSspAssetsAttributesTransfer = $this->mapSspAssetTransferToRestSspAssetsAttributesTransfer($sspAssetTransfer);
        $assetResource = $this->createSspAssetRestResource($restSspAssetsAttributesTransfer, $sspAssetTransfer->getReferenceOrFail());
        $restResponse->addResource($assetResource);

        return $restResponse;
    }

    public function createAssetRestResponseFromSspAssetCollectionResponseTransfer(
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer,
        string $localeName
    ): RestResponseInterface {
        $errors = $sspAssetCollectionResponseTransfer->getErrors();
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $errorTransfer) {
            $errorMessageStatus = static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[$errorTransfer->getMessage()] ?? static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[static::GLOSSARY_KEY_UNKNOWN_ERROR];

            $errorTransfer = (new RestErrorMessageTransfer())
                ->setStatus($errorMessageStatus)
                ->setDetail($this->glossaryStorageClient->translate($errorTransfer->getMessageOrFail(), $localeName));

            $restResponse->addError($errorTransfer);
        }

        $sspAssetTransfers = $sspAssetCollectionResponseTransfer->getSspAssets();

        foreach ($sspAssetTransfers as $sspAssetTransfer) {
            $restSspAssetsAttributesTransfer = $this->mapSspAssetTransferToRestSspAssetsAttributesTransfer($sspAssetTransfer);
            $assetResource = $this->createSspAssetRestResource($restSspAssetsAttributesTransfer, $sspAssetTransfer->getReferenceOrFail());
            $restResponse->addResource($assetResource);
        }

        return $restResponse;
    }

    protected function createAssetNotFoundErrorResponse(string $localeName): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $errorTransfer = (new RestErrorMessageTransfer())
            ->setStatus(static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[static::GLOSSARY_KEY_ERROR_ASSET_NOT_FOUND])
            ->setDetail($this->glossaryStorageClient->translate(static::GLOSSARY_KEY_ERROR_ASSET_NOT_FOUND, $localeName));
        $restResponse->addError($errorTransfer);

        return $restResponse;
    }

    protected function createSspAssetRestResource(RestSspAssetsAttributesTransfer $restSspAssetsAttributesTransfer, string $reference): RestResourceInterface
    {
        return $this->restResourceBuilder->createRestResource(
            SelfServicePortalConfig::RESOURCE_SSP_ASSETS,
            $reference,
            $restSspAssetsAttributesTransfer,
        );
    }

    protected function mapSspAssetTransferToRestSspAssetsAttributesTransfer(SspAssetTransfer $sspAssetTransfer): RestSspAssetsAttributesTransfer
    {
        $restSspAssetsAttributesTransfer = new RestSspAssetsAttributesTransfer();
        $restSspAssetsAttributesTransfer->fromArray($sspAssetTransfer->toArray(), true);
        $restSspAssetsAttributesTransfer->setBusinessUnitAssignments(new ArrayObject());

        foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $sspAssetBusinessUnitAssignmentTransfer) {
            $restSspAssetsAttributesTransfer->addBusinessUnitAssignment(
                (new RestSspAssetBusinessUnitAssignmentTransfer())->setIdCompanyBusinessUnit($sspAssetBusinessUnitAssignmentTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail()),
            );
        }

        return $restSspAssetsAttributesTransfer;
    }
}
