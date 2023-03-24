<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
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

            $apiPickingListItemsAttributesTransfer = $this->mapPickingListItemToApiPickingListItemsAttributesTransfer(
                $pickingListItem,
                new ApiPickingListItemsAttributesTransfer(),
            );
            $glueResourceTransfer->setAttributes($apiPickingListItemsAttributesTransfer);

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
     * @param \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer $apiPickingListItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer
     */
    protected function mapPickingListItemToApiPickingListItemsAttributesTransfer(
        stdClass $pickingListItem,
        ApiPickingListItemsAttributesTransfer $apiPickingListItemsAttributesTransfer
    ): ApiPickingListItemsAttributesTransfer {
        if (!property_exists($pickingListItem, static::PROPERTY_ATTRIBUTES)) {
            return $apiPickingListItemsAttributesTransfer;
        }

        $attributes = $pickingListItem->attributes;

        if (property_exists($attributes, static::PROPERTY_NUMBER_OF_PICKED)) {
            $apiPickingListItemsAttributesTransfer->setNumberOfPicked($attributes->numberOfPicked);
        }

        if (property_exists($attributes, static::PROPERTY_NUMBER_OF_NOT_PICKED)) {
            $apiPickingListItemsAttributesTransfer->setNumberOfNotPicked($attributes->numberOfNotPicked);
        }

        return $apiPickingListItemsAttributesTransfer;
    }
}
