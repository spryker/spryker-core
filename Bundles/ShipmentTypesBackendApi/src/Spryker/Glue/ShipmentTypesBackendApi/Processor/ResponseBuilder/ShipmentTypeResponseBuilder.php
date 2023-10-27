<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Translator\ShipmentTypeTranslatorInterface;
use Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShipmentTypeResponseBuilder implements ShipmentTypeResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig
     */
    protected ShipmentTypesBackendApiConfig $shipmentTypesBackendApiConfig;

    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface
     */
    protected ShipmentTypeMapperInterface $shipmentTypeMapper;

    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Processor\Translator\ShipmentTypeTranslatorInterface
     */
    protected ShipmentTypeTranslatorInterface $shipmentTypeTranslator;

    /**
     * @param \Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig $shipmentTypesBackendApiConfig
     * @param \Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface $shipmentTypeMapper
     * @param \Spryker\Glue\ShipmentTypesBackendApi\Processor\Translator\ShipmentTypeTranslatorInterface $shipmentTypeTranslator
     */
    public function __construct(
        ShipmentTypesBackendApiConfig $shipmentTypesBackendApiConfig,
        ShipmentTypeMapperInterface $shipmentTypeMapper,
        ShipmentTypeTranslatorInterface $shipmentTypeTranslator
    ) {
        $this->shipmentTypesBackendApiConfig = $shipmentTypesBackendApiConfig;
        $this->shipmentTypeMapper = $shipmentTypeMapper;
        $this->shipmentTypeTranslator = $shipmentTypeTranslator;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createShipmentTypeResponse(
        ArrayObject $shipmentTypeTransfers,
        ?PaginationTransfer $paginationTransfer = null
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $glueResponseTransfer->addResource(
                $this->createShipmentTypeResourceTransfer($shipmentTypeTransfer),
            );
        }

        if ($paginationTransfer) {
            $glueResponseTransfer->setPagination($paginationTransfer);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createErrorResponse(ArrayObject $errorTransfers, ?string $localeName = null): GlueResponseTransfer
    {
        $errorDataIndexedByGlossaryKey = $this->shipmentTypesBackendApiConfig->getErrorDataIndexedByGlossaryKey();
        $translations = $this->shipmentTypeTranslator->translateErrorTransferMessages(
            $errorTransfers,
            $localeName,
        );

        return $this->createGlueResponseTransferWithErrors($errorTransfers, $translations, $errorDataIndexedByGlossaryKey);
    }

    /**
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    public function createEntityNotFoundErrorTransfer(): ErrorTransfer
    {
        return (new ErrorTransfer())->setMessage(ShipmentTypesBackendApiConfig::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createShipmentTypeResourceTransfer(ShipmentTypeTransfer $shipmentTypeTransfer): GlueResourceTransfer
    {
        $shipmentTypesBackendApiAttributesTransfer = $this->shipmentTypeMapper->mapShipmentTypeTransferToShipmentTypesBackendApiAttributesTransfer(
            $shipmentTypeTransfer,
            new ShipmentTypesBackendApiAttributesTransfer(),
        );

        return (new GlueResourceTransfer())
            ->setId($shipmentTypeTransfer->getUuidOrFail())
            ->setType(ShipmentTypesBackendApiConfig::RESOURCE_SHIPMENT_TYPES)
            ->setAttributes($shipmentTypesBackendApiAttributesTransfer);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param array<string, string> $translations
     * @param array<string, array<string, mixed>> $errorDataIndexedByGlossaryKey
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function createGlueResponseTransferWithErrors(
        ArrayObject $errorTransfers,
        array $translations,
        array $errorDataIndexedByGlossaryKey
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();
        foreach ($errorTransfers as $errorTransfer) {
            $glueErrorTransfer = $this->createGlueErrorTransfer(
                $errorTransfer->getMessageOrFail(),
                $translations,
                $errorDataIndexedByGlossaryKey,
            );

            $glueResponseTransfer->addError($glueErrorTransfer);
        }

        return $this->setGlueResponseHttpStatus($glueResponseTransfer);
    }

    /**
     * @param string $glossaryKey
     * @param array<string, string> $translations
     * @param array<string, array<string, mixed>> $errorDataIndexedByGlossaryKey
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createGlueErrorTransfer(
        string $glossaryKey,
        array $translations,
        array $errorDataIndexedByGlossaryKey
    ): GlueErrorTransfer {
        if (!isset($errorDataIndexedByGlossaryKey[$glossaryKey])) {
            return $this->createUnknownGlueErrorTransfer($translations[$glossaryKey]);
        }

        return (new GlueErrorTransfer())
            ->fromArray($errorDataIndexedByGlossaryKey[$glossaryKey])
            ->setMessage($translations[$glossaryKey]);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createUnknownGlueErrorTransfer(string $message): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setMessage($message)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(ShipmentTypesBackendApiConfig::RESPONSE_CODE_UNKNOWN_ERROR);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function setGlueResponseHttpStatus(GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        $glueErrorTransfers = $glueResponseTransfer->getErrors();
        if ($glueErrorTransfers->count() > 1) {
            return $glueResponseTransfer->setHttpStatus(
                Response::HTTP_MULTI_STATUS,
            );
        }

        return $glueResponseTransfer->setHttpStatus(
            $glueErrorTransfers->getIterator()->current()->getStatus(),
        );
    }
}
