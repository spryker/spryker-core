<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Strategy;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface;

abstract class AbstractPickingListUpdateStrategy implements PickingListUpdateStrategyInterface
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
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface $pickingListReader
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface $pickingListMapper
     * @param \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade
     */
    public function __construct(
        PickingListReaderInterface $pickingListReader,
        PickingListMapperInterface $pickingListMapper,
        PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade
    ) {
        $this->pickingListReader = $pickingListReader;
        $this->pickingListMapper = $pickingListMapper;
        $this->pickingListFacade = $pickingListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function update(GlueRequestTransfer $glueRequestTransfer): PickingListCollectionResponseTransfer
    {
        $pickingListTransfer = $this->pickingListReader->getPickingListByPickingListUuid(
            $glueRequestTransfer->getResourceOrFail()->getIdOrFail(),
        );

        $pickingListTransfer = $this->pickingListMapper
            ->mapGlueRequestTransferToPickingListTransfer($glueRequestTransfer, $pickingListTransfer);

        return $this->pickingListFacade
            ->updatePickingListCollection(
                $this->createPickingListCollectionRequestTransfer($pickingListTransfer),
            );
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
