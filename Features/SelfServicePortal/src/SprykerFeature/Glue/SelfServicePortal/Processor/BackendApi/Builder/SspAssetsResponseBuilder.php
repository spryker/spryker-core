<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Builder;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Mapper\SspAssetsMapperInterface;
use SprykerFeature\Glue\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\HttpFoundation\Response;

class SspAssetsResponseBuilder implements SspAssetsResponseBuilderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ASSET_NOT_FOUND = 'self_service_portal.asset.validation.asset_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND = 'self_service_portal.validation.entity_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY = 'self_service_portal.validation.wrong_request_body';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_BUSINESS_UNIT_NOT_FOUND = 'self_service_portal.validation.business_unit_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_UNKNOWN_ERROR = 'self_service_portal.asset.validation.unknown_error';

    /**
     * @var string
     */
    protected const MESSAGE_ASSET_NOT_FOUND = 'Asset not found';

    /**
     * @var array<string, int>
     */
    protected const GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING = [
        self::GLOSSARY_KEY_ASSET_NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY => Response::HTTP_BAD_REQUEST,
        self::GLOSSARY_KEY_VALIDATION_BUSINESS_UNIT_NOT_FOUND => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::GLOSSARY_KEY_UNKNOWN_ERROR => Response::HTTP_BAD_REQUEST,
    ];

    public function __construct(
        protected AbstractBundleConfig $config,
        protected GlossaryStorageClientInterface $glossaryStorageClient,
        protected SspAssetsMapperInterface $sspAssetsMapper
    ) {
    }

    public function createSspAssetCollectionResponse(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $glueResponseTransfer->addResource($this->createSspAssetResource($sspAssetTransfer));
        }

        return $glueResponseTransfer;
    }

    public function createSspAssetResponse(
        SspAssetTransfer $sspAssetTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return (new GlueResponseTransfer())
            ->addResource($this->createSspAssetResource($sspAssetTransfer));
    }

    public function createAssetNotFoundErrorResponse(string $localeName): GlueResponseTransfer
    {
        $errorStatus = static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[static::GLOSSARY_KEY_ASSET_NOT_FOUND];

        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setStatus($errorStatus)
            ->setCode(SelfServicePortalConfig::RESOURCE_SSP_ASSETS)
            ->setMessage($this->glossaryStorageClient->translate(static::MESSAGE_ASSET_NOT_FOUND, $localeName));

        return (new GlueResponseTransfer())
            ->addError($glueErrorTransfer)
            ->setHttpStatus($errorStatus);
    }

    public function createErrorResponseFromAssetCollectionResponse(
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer,
        string $localeName
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($sspAssetCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $errorMessageStatus = static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[$errorTransfer->getMessageOrFail()] ?? static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[static::GLOSSARY_KEY_UNKNOWN_ERROR];

            $glueErrorTransfer = (new GlueErrorTransfer())
                ->setStatus($errorMessageStatus)
                ->setMessage($this->glossaryStorageClient->translate($errorTransfer->getMessageOrFail(), $localeName));

            $glueResponseTransfer->addError($glueErrorTransfer);
        }

        return $glueResponseTransfer->setHttpStatus(Response::HTTP_BAD_REQUEST);
    }

    protected function createSspAssetResource(SspAssetTransfer $sspAssetTransfer): GlueResourceTransfer
    {
        $sspAssetsBackendApiAttributesTransfer = $this->sspAssetsMapper
            ->mapSspAssetTransferToSspAssetsBackendApiAttributesTransfer($sspAssetTransfer);

        return (new GlueResourceTransfer())
            ->setType(SelfServicePortalConfig::RESOURCE_SSP_ASSETS)
            ->setId($sspAssetTransfer->getReferenceOrFail())
            ->setAttributes($sspAssetsBackendApiAttributesTransfer);
    }
}
