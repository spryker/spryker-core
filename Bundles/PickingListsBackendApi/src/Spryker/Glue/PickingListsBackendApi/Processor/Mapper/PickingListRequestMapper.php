<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer;
use stdClass;

class PickingListRequestMapper implements PickingListRequestMapperInterface
{
    /**
     * @var string
     */
    protected const PROPERTY_DATA = 'data';

    /**
     * @var string
     */
    protected const PROPERTY_ATTRIBUTES = 'attributes';

    /**
     * @var string
     */
    protected const PROPERTY_ID = 'id';

    /**
     * @var string
     */
    protected const PROPERTY_TYPE = 'type';

    /**
     * @var string
     */
    protected const PROPERTY_NUMBER_OF_PICKED = 'numberOfPicked';

    /**
     * @var string
     */
    protected const PROPERTY_NUMBER_OF_NOT_PICKED = 'numberOfNotPicked';

    /**
     * @param \stdClass $requestBody
     * @param \ArrayObject<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransferCollection
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function mapRequestBodyToGlueResourceTransferCollection(
        stdClass $requestBody,
        ArrayObject $glueResourceTransferCollection
    ): ArrayObject {
        if (!property_exists($requestBody, static::PROPERTY_DATA) || !is_array($requestBody->data)) {
            return $glueResourceTransferCollection;
        }

        foreach ($requestBody->data as $pickingListItem) {
            $glueResourceTransfer = $this->mapPickingListItemtoGlueResourceTransfer(
                $pickingListItem,
                new GlueResourceTransfer(),
            );

            $pickingListItemsBackendApiAttributesTransfer = $this->mapPickingListItemToPickingListItemsBackendApiAttributesTransfer(
                $pickingListItem,
                new PickingListItemsBackendApiAttributesTransfer(),
            );
            $glueResourceTransfer->setAttributes($pickingListItemsBackendApiAttributesTransfer);

            $glueResourceTransferCollection->append($glueResourceTransfer);
        }

        return $glueResourceTransferCollection;
    }

    /**
     * @param \stdClass $pickingListItem
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapPickingListItemtoGlueResourceTransfer(
        stdClass $pickingListItem,
        GlueResourceTransfer $glueResourceTransfer
    ): GlueResourceTransfer {
        if (property_exists($pickingListItem, static::PROPERTY_ID)) {
            $glueResourceTransfer->setId($pickingListItem->id);
        }

        if (property_exists($pickingListItem, static::PROPERTY_TYPE)) {
            $glueResourceTransfer->setType($pickingListItem->type);
        }

        return $glueResourceTransfer;
    }

    /**
     * @param \stdClass $pickingListItem
     * @param \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer
     */
    protected function mapPickingListItemToPickingListItemsBackendApiAttributesTransfer(
        stdClass $pickingListItem,
        PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer
    ): PickingListItemsBackendApiAttributesTransfer {
        if (!property_exists($pickingListItem, static::PROPERTY_ATTRIBUTES)) {
            return $pickingListItemsBackendApiAttributesTransfer;
        }

        $attributes = $pickingListItem->attributes;

        if (property_exists($attributes, static::PROPERTY_NUMBER_OF_PICKED)) {
            $pickingListItemsBackendApiAttributesTransfer->setNumberOfPicked($attributes->numberOfPicked);
        }

        if (property_exists($attributes, static::PROPERTY_NUMBER_OF_NOT_PICKED)) {
            $pickingListItemsBackendApiAttributesTransfer->setNumberOfNotPicked($attributes->numberOfNotPicked);
        }

        return $pickingListItemsBackendApiAttributesTransfer;
    }
}
