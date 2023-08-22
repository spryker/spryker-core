<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PickingListsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\GlossaryReaderInterface;
use Symfony\Component\HttpFoundation\Response;

class PickingListResponseCreator implements PickingListResponseCreatorInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig
     */
    protected PickingListsBackendApiConfig $pickingListBackendApiConfig;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface
     */
    protected PickingListMapperInterface $pickingListMapper;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Reader\GlossaryReaderInterface
     */
    protected GlossaryReaderInterface $glossaryReader;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig $pickingListBackendApiConfig
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface $pickingListMapper
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Reader\GlossaryReaderInterface $glossaryReader
     */
    public function __construct(
        PickingListsBackendApiConfig $pickingListBackendApiConfig,
        PickingListMapperInterface $pickingListMapper,
        GlossaryReaderInterface $glossaryReader
    ) {
        $this->pickingListBackendApiConfig = $pickingListBackendApiConfig;
        $this->pickingListMapper = $pickingListMapper;
        $this->glossaryReader = $glossaryReader;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPickingListSuccessfulResponse(
        ArrayObject $pickingListTransferCollection
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();
        foreach ($pickingListTransferCollection as $pickingListTransfer) {
            $glueResponseTransfer->addResource(
                $this->createPickingListResourceTransfer($pickingListTransfer),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPickingListErrorResponse(
        ArrayObject $errorTransfers,
        ?string $localeName
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();
        $validationGlossaryKeyToRestErrorMapping = $this->pickingListBackendApiConfig
            ->getValidationGlossaryKeyToRestErrorMapping();

        foreach ($errorTransfers as $errorTransfer) {
            $glueResponseTransfer = $this->expandGlueResponseTransferWithError(
                $errorTransfer,
                $glueResponseTransfer,
                $validationGlossaryKeyToRestErrorMapping,
            );
        }

        $glueResponseTransfer = $this->setGlueResponseHttpStatus($glueResponseTransfer);

        return $this->glossaryReader
            ->translateGlueResponseTransfer(
                $glueResponseTransfer,
                $localeName ?? PickingListsBackendApiConfig::DEFAULT_LOCALE,
            );
    }

    /**
     * @param string $message
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPickingListSingleErrorResponse(
        string $message,
        ?string $localeName
    ): GlueResponseTransfer {
        $errorTransferCollection = new ArrayObject();
        $errorTransferCollection->append(
            $this->createErrorTransfer($message),
        );

        return $this->createPickingListErrorResponse($errorTransferCollection, $localeName);
    }

    /**
     * @param array<string, mixed> $restError
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function mapRestErrorToGlueErrorTransfer(array $restError): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->fromArray($restError, true);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createPickingListResourceTransfer(
        PickingListTransfer $pickingListTransfer
    ): GlueResourceTransfer {
        $pickingListsBackendApiAttributesTransfer = $this->pickingListMapper
            ->mapPickingListTransferToPickingListsBackendApiAttributesTransfer(
                $pickingListTransfer,
                new PickingListsBackendApiAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($pickingListTransfer->getUuid())
            ->setType(PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS)
            ->setAttributes($pickingListsBackendApiAttributesTransfer);
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
            ->setCode('');
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(string $message): ErrorTransfer
    {
        return (new ErrorTransfer())->setMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function setGlueResponseHttpStatus(GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        /** @var \ArrayObject<\Generated\Shared\Transfer\GlueErrorTransfer> $errorTransferCollection */
        $errorTransferCollection = $glueResponseTransfer->getErrors();

        if ($errorTransferCollection->count() !== 1) {
            return $glueResponseTransfer->setHttpStatus(
                Response::HTTP_MULTI_STATUS,
            );
        }

        $errorTransfer = $errorTransferCollection->getIterator()->current();

        return $glueResponseTransfer->setHttpStatus(
            $errorTransfer->getStatus(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param array<string, array<string, mixed>> $validationGlossaryKeyToRestErrorMapping
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function expandGlueResponseTransferWithError(
        ErrorTransfer $errorTransfer,
        GlueResponseTransfer $glueResponseTransfer,
        array $validationGlossaryKeyToRestErrorMapping
    ): GlueResponseTransfer {
        $message = $errorTransfer->getMessageOrFail();

        if (!isset($validationGlossaryKeyToRestErrorMapping[$message])) {
            return $glueResponseTransfer->addError(
                $this->createUnknownGlueErrorTransfer($message),
            );
        }

        return $glueResponseTransfer->addError(
            $this->mapRestErrorToGlueErrorTransfer($validationGlossaryKeyToRestErrorMapping[$message]),
        );
    }
}
