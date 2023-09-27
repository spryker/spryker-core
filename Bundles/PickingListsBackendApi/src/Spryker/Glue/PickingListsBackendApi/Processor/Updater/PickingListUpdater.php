<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface;

class PickingListUpdater implements PickingListUpdaterInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface
     */
    protected PickingListReaderInterface $pickingListReader;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface
     */
    protected PickingListMapperInterface $pickingListMapper;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface
     */
    protected PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface
     */
    protected PickingListResponseCreatorInterface $pickingListResponseCreator;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface $pickingListReader
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface $pickingListMapper
     * @param \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface $pickingListResponseCreator
     */
    public function __construct(
        PickingListReaderInterface $pickingListReader,
        PickingListMapperInterface $pickingListMapper,
        PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade,
        PickingListResponseCreatorInterface $pickingListResponseCreator
    ) {
        $this->pickingListReader = $pickingListReader;
        $this->pickingListMapper = $pickingListMapper;
        $this->pickingListFacade = $pickingListFacade;
        $this->pickingListResponseCreator = $pickingListResponseCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function startPicking(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $glueResourceTransfer = $glueRequestTransfer->getParentResources()->getIterator()->current() ?? new GlueResourceTransfer();
        $pickingListTransfer = $this->pickingListReader->getPickingListByPickingListUuid($glueResourceTransfer->getIdOrFail());
        $pickingListTransfer = $this->pickingListMapper->mapGlueRequestTransferToPickingListTransfer($glueRequestTransfer, $pickingListTransfer);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        return $this->createPickingListUpdateResponse(
            $glueRequestTransfer,
            $this->pickingListFacade->updatePickingListCollection($pickingListCollectionRequestTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function createPickingListUpdateResponse(
        GlueRequestTransfer $glueRequestTransfer,
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): GlueResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $pickingListCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->pickingListResponseCreator->createPickingListErrorResponse($errorTransfers, $glueRequestTransfer->getLocale());
        }

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers */
        $pickingListTransfers = $pickingListCollectionResponseTransfer->getPickingLists();

        return $this->pickingListResponseCreator->createPickingListSuccessfulResponse($pickingListTransfers);
    }
}
