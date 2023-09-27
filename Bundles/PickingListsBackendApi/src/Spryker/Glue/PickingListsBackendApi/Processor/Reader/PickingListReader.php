<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListConditionsExpanderInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface;

class PickingListReader implements PickingListReaderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface
     */
    protected PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface
     */
    protected PickingListMapperInterface $pickingListMapper;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface
     */
    protected PickingListResponseCreatorInterface $pickingListResponseCreator;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListConditionsExpanderInterface
     */
    protected PickingListConditionsExpanderInterface $pickingListConditionsExpander;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface $pickingListMapper
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface $pickingListResponseCreator
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListConditionsExpanderInterface $pickingListConditionsExpander
     */
    public function __construct(
        PickingListsBackendApiToPickingListFacadeInterface $pickingListFacade,
        PickingListMapperInterface $pickingListMapper,
        PickingListResponseCreatorInterface $pickingListResponseCreator,
        PickingListConditionsExpanderInterface $pickingListConditionsExpander
    ) {
        $this->pickingListFacade = $pickingListFacade;
        $this->pickingListMapper = $pickingListMapper;
        $this->pickingListResponseCreator = $pickingListResponseCreator;
        $this->pickingListConditionsExpander = $pickingListConditionsExpander;
    }

    /**
     * @param string $pickingListUuid
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function getPickingListByPickingListUuid(string $pickingListUuid): PickingListTransfer
    {
        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())->setPickingListConditions(
            (new PickingListConditionsTransfer())->addUuid($pickingListUuid),
        );

        $pickingListCollectionTransfer = $this->pickingListFacade
            ->getPickingListCollection($pickingListCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionTransfer->getPickingLists();
        if ($pickingListTransferCollection->count() === 0) {
            return (new PickingListTransfer())->setUuid($pickingListUuid);
        }

        return $pickingListTransferCollection->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getPickingListCollection(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $pickingListConditionsTransfer = $this->pickingListConditionsExpander->expandWithPickingListCollectionRequestData(
            new PickingListConditionsTransfer(),
            $glueRequestTransfer,
        );
        $pickingListCriteriaTransfer = $this->pickingListMapper
            ->mapGlueRequestTransferToPickingListCriteriaTransfer(
                $glueRequestTransfer,
                (new PickingListCriteriaTransfer())->setPickingListConditions($pickingListConditionsTransfer),
            );

        $pickingListCollectionTransfer = $this->pickingListFacade
            ->getPickingListCollection($pickingListCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\PickingListTransfer> $pickingLists */
        $pickingLists = $pickingListCollectionTransfer->getPickingLists();

        return $this->pickingListResponseCreator
            ->createPickingListSuccessfulResponse($pickingLists);
    }

    /**
     * @param string $uuid
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getPickingList(
        string $uuid,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $pickingListConditionsTransfer = $this->pickingListMapper
            ->mapGlueRequestTransferToPickingListConditionsTransfer(
                $glueRequestTransfer,
                (new PickingListConditionsTransfer())->addUuid($uuid),
            );
        $pickingListConditionsTransfer = $this->pickingListConditionsExpander->expandWithPickingListRequestData(
            $pickingListConditionsTransfer,
            $glueRequestTransfer,
        );
        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())->setPickingListConditions($pickingListConditionsTransfer);
        $pickingListCollectionTransfer = $this->pickingListFacade->getPickingListCollection($pickingListCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers */
        $pickingListTransfers = $pickingListCollectionTransfer->getPickingLists();
        if ($pickingListTransfers->count() < 1) {
            return $this->pickingListResponseCreator
                ->createPickingListSingleErrorResponse(
                    PickingListsBackendApiConfig::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND,
                    $glueRequestTransfer->getLocale(),
                );
        }

        return $this->pickingListResponseCreator
            ->createPickingListSuccessfulResponse($pickingListTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param list<string> $pickingListUuids
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getPickingListCollectionByPickingListUuids(
        GlueRequestTransfer $glueRequestTransfer,
        array $pickingListUuids
    ): PickingListCollectionTransfer {
        $pickingListConditionsTransfer = $this->pickingListMapper->mapGlueRequestTransferToPickingListConditionsTransfer(
            $glueRequestTransfer,
            new PickingListConditionsTransfer(),
        )->setUuids($pickingListUuids);

        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())
            ->setPickingListConditions($pickingListConditionsTransfer);

        $pickingListCriteriaTransfer = $this->pickingListMapper
            ->mapGlueRequestTransferToPickingListCriteriaTransfer(
                $glueRequestTransfer,
                $pickingListCriteriaTransfer,
            );

        return $this->pickingListFacade->getPickingListCollection($pickingListCriteriaTransfer);
    }
}
