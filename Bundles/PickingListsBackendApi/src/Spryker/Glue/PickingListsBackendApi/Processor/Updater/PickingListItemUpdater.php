<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Updater;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Service\PickingListsBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListRequestMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface;
use stdClass;

class PickingListItemUpdater implements PickingListItemUpdaterInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface
     */
    protected PickingListReaderInterface $pickingListReader;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface
     */
    protected PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Dependency\Service\PickingListsBackendApiToUtilEncodingServiceInterface
     */
    protected PickingListsBackendApiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface
     */
    protected PickingListMapperInterface $pickingListMapper;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface
     */
    protected PickingListResponseCreatorInterface $pickingListResponseCreator;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListRequestMapperInterface
     */
    protected PickingListRequestMapperInterface $pickingListRequestMapper;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface $pickingListReader
     * @param \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade
     * @param \Spryker\Glue\PickingListsBackendApi\Dependency\Service\PickingListsBackendApiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface $pickingListMapper
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface $pickingListResponseCreator
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListRequestMapperInterface $pickingListRequestMapper
     */
    public function __construct(
        PickingListReaderInterface $pickingListReader,
        PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade,
        PickingListsBackendApiToUtilEncodingServiceInterface $utilEncodingService,
        PickingListMapperInterface $pickingListMapper,
        PickingListResponseCreatorInterface $pickingListResponseCreator,
        PickingListRequestMapperInterface $pickingListRequestMapper
    ) {
        $this->pickingListReader = $pickingListReader;
        $this->pickingListFacade = $pickingListFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->pickingListMapper = $pickingListMapper;
        $this->pickingListResponseCreator = $pickingListResponseCreator;
        $this->pickingListRequestMapper = $pickingListRequestMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updatePickingListItems(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $pickingListTransfer = $this->pickingListReader
            ->getPickingListByPickingListUuid(
                $this->getParentGlueResourceTransfer($glueRequestTransfer)->getIdOrFail(),
            );

        $pickingListTransfer = $this->pickingListMapper
            ->mapGlueRequestTransferToPickingListTransfer($glueRequestTransfer, $pickingListTransfer);

        $requestBody = $this->getRequestBody($glueRequestTransfer);
        if (!$requestBody) {
            return $this->pickingListResponseCreator
                ->createPickingListSingleErrorResponse(
                    PickingListsBackendApiConfig::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY,
                    $glueRequestTransfer->getLocale(),
                );
        }

        $glueResourceTransferCollection = $this->pickingListRequestMapper
            ->mapRequestBodyToGlueResourceTransferCollection(
                $requestBody,
                new ArrayObject(),
            );

        $pickingListTransfer = $this->pickingListMapper
            ->mapPickingListItemGlueResourceTransferCollectionToPickingListTransfer(
                $glueResourceTransferCollection,
                $pickingListTransfer,
            );

        $pickingListCollectionResponseTransfer = $this->pickingListFacade
            ->updatePickingListCollection(
                $this->createPickingListCollectionRequestTransfer($pickingListTransfer),
            );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransferCollection */
        $errorTransferCollection = $pickingListCollectionResponseTransfer->getErrors();
        if ($errorTransferCollection->count() !== 0) {
            return $this->pickingListResponseCreator
                ->createPickingListErrorResponse(
                    $errorTransferCollection,
                    $glueRequestTransfer->getLocale(),
                );
        }

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionResponseTransfer->getPickingLists();

        return $this->pickingListResponseCreator
            ->createPickingListSuccessfulResponse($pickingListTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function getParentGlueResourceTransfer(GlueRequestTransfer $glueRequestTransfer): GlueResourceTransfer
    {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\GlueResourceTransfer|null> $parentResourceCollection */
        $parentResourceCollection = $glueRequestTransfer->getParentResources();

        return $parentResourceCollection->getIterator()->current() ?? new GlueResourceTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \stdClass|null
     */
    protected function getRequestBody(GlueRequestTransfer $glueRequestTransfer): ?stdClass
    {
        $requestBody = $glueRequestTransfer->getContent();
        if ($requestBody === null) {
            return null;
        }

        return $this->utilEncodingService->decodeJson($requestBody);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionRequestTransfer
     */
    protected function createPickingListCollectionRequestTransfer(
        PickingListTransfer $pickingListTransfer
    ): PickingListCollectionRequestTransfer {
        return (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);
    }
}
