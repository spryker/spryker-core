<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiPickingListsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Glue\PickingListsBackendApi\Processor\Grouper\PickingListItemGrouperInterface;

class PickingListMapper implements PickingListMapperInterface
{
    /**
     * @var string
     */
    protected const FILTER_FIELD_PICKING_LISTS_STATUS = 'status';

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListItemMapperInterface
     */
    protected PickingListItemMapperInterface $pickingListItemMapper;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\UserMapperInterface
     */
    protected UserMapperInterface $userMapper;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Grouper\PickingListItemGrouperInterface
     */
    protected PickingListItemGrouperInterface $pickingListItemGrouper;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListItemMapperInterface $pickingListItemMapper
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\UserMapperInterface $userMapper
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Grouper\PickingListItemGrouperInterface $pickingListItemGrouper
     */
    public function __construct(
        PickingListItemMapperInterface $pickingListItemMapper,
        UserMapperInterface $userMapper,
        PickingListItemGrouperInterface $pickingListItemGrouper
    ) {
        $this->pickingListItemMapper = $pickingListItemMapper;
        $this->userMapper = $userMapper;
        $this->pickingListItemGrouper = $pickingListItemGrouper;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function mapGlueRequestTransferToPickingListTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PickingListTransfer $pickingListTransfer
    ): PickingListTransfer {
        return $pickingListTransfer->setUser(
            $this->userMapper->mapGlueRequestTransferToUserTransfer($glueRequestTransfer, new UserTransfer()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Generated\Shared\Transfer\ApiPickingListsAttributesTransfer $apiPickingListsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPickingListsAttributesTransfer
     */
    public function mapPickingListTransferToApiPickingListsAttributesTransfer(
        PickingListTransfer $pickingListTransfer,
        ApiPickingListsAttributesTransfer $apiPickingListsAttributesTransfer
    ): ApiPickingListsAttributesTransfer {
        return $apiPickingListsAttributesTransfer->fromArray(
            $pickingListTransfer->toArray(),
            true,
        );
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransferCollection
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function mapPickingListItemGlueResourceTransferCollectionToPickingListTransfer(
        ArrayObject $glueResourceTransferCollection,
        PickingListTransfer $pickingListTransfer
    ): PickingListTransfer {
        $pickingListItemTransferCollectionIndexedByUuid = $this->pickingListItemGrouper
            ->getPickingListItemTransferCollectionIndexedByUuid($pickingListTransfer);
        $pickingListTransfer->setPickingListItems(new ArrayObject());

        foreach ($glueResourceTransferCollection as $glueResourceTransfer) {
            $pickingListItemTransfer = $this->getPickingListItemTransfer(
                $pickingListItemTransferCollectionIndexedByUuid,
                $glueResourceTransfer,
            );

            $pickingListItemTransfer = $this->pickingListItemMapper
                ->mapPickingListItemGlueResourceTransferToPickingListItemTransfer(
                    $glueResourceTransfer,
                    $pickingListItemTransfer,
                );

            $pickingListTransfer->addPickingListItem($pickingListItemTransfer);
        }

        return $pickingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCriteriaTransfer
     */
    public function mapGlueRequestTransferToPickingListCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCriteriaTransfer {
        $this->mapGlueRequestTransferToPickingListConditionsTransfer($glueRequestTransfer, $pickingListCriteriaTransfer->getPickingListConditionsOrFail());

        $pickingListCriteriaTransfer->setPagination($glueRequestTransfer->getPagination())
            ->setSortCollection($glueRequestTransfer->getSortings());

        return $pickingListCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PickingListConditionsTransfer $pickingListConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListConditionsTransfer
     */
    public function mapGlueRequestTransferToPickingListConditionsTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PickingListConditionsTransfer $pickingListConditionsTransfer
    ): PickingListConditionsTransfer {
        foreach ($glueRequestTransfer->getFilters() as $glueFilterTransfer) {
            if ($glueFilterTransfer->getField() !== static::FILTER_FIELD_PICKING_LISTS_STATUS) {
                continue;
            }

            $pickingListConditionsTransfer->addStatus($glueFilterTransfer->getValueOrFail());
        }

        return $pickingListConditionsTransfer;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransferCollectionIndexedByUuid
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    protected function getPickingListItemTransfer(
        array $pickingListItemTransferCollectionIndexedByUuid,
        GlueResourceTransfer $glueResourceTransfer
    ): PickingListItemTransfer {
        $pickingListItemUuid = $glueResourceTransfer->getId();
        if (!$pickingListItemUuid || !isset($pickingListItemTransferCollectionIndexedByUuid[$pickingListItemUuid])) {
            return new PickingListItemTransfer();
        }

        return $pickingListItemTransferCollectionIndexedByUuid[$pickingListItemUuid];
    }
}
