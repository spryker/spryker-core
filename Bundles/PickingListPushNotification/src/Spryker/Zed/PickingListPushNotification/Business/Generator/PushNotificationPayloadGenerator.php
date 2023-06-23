<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Generator;

use Spryker\Zed\PickingListPushNotification\Business\Extractor\PickingListExtractorInterface;

class PushNotificationPayloadGenerator implements PushNotificationPayloadGeneratorInterface
{
    /**
     * @var string
     */
    protected const PAYLOAD_KEY_ACTION = 'action';

    /**
     * @var string
     */
    protected const PAYLOAD_KEY_ENTITY = 'entity';

    /**
     * @var string
     */
    protected const PAYLOAD_KEY_IDS = 'ids';

    /**
     * @var string
     */
    protected const RESOURCE_PICKING_LISTS = 'picking-lists';

    /**
     * @var \Spryker\Zed\PickingListPushNotification\Business\Extractor\PickingListExtractorInterface
     */
    protected PickingListExtractorInterface $pickingListExtractor;

    /**
     * @param \Spryker\Zed\PickingListPushNotification\Business\Extractor\PickingListExtractorInterface $pickingListExtractor
     */
    public function __construct(PickingListExtractorInterface $pickingListExtractor)
    {
        $this->pickingListExtractor = $pickingListExtractor;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     * @param string $action
     *
     * @return array<string, mixed>
     */
    public function generatePushNotificationPayload(
        array $pickingListTransfers,
        string $action
    ): array {
        return [
            static::PAYLOAD_KEY_ACTION => $action,
            static::PAYLOAD_KEY_ENTITY => static::RESOURCE_PICKING_LISTS,
            static::PAYLOAD_KEY_IDS => $this->pickingListExtractor->extractUuids($pickingListTransfers),
        ];
    }
}
