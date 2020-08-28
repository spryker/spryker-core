<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model;

use Generated\Shared\Transfer\TouchTransfer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Spryker\Zed\Touch\TouchConfig;

class Touch implements TouchInterface
{
    public const BULK_UPDATE_CHUNK_SIZE = 250;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\TouchConfig
     */
    protected $touchConfig;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Touch\TouchConfig $touchConfig
     */
    public function __construct(
        TouchQueryContainerInterface $queryContainer,
        TouchConfig $touchConfig
    ) {
        $this->touchQueryContainer = $queryContainer;
        $this->touchConfig = $touchConfig;
    }

    /**
     * @param string $itemType
     *
     * @return \Generated\Shared\Transfer\TouchTransfer[]
     */
    public function getItemsByType($itemType)
    {
        if (!$this->touchConfig->isTouchEnabled()) {
            return [];
        }

        $entityList = $this->touchQueryContainer
            ->queryTouchListByItemType($itemType)
            ->find();

        $items = [];
        foreach ($entityList as $entity) {
            $touchTransfer = (new TouchTransfer())
                ->fromArray($entity->toArray());

            $items[$entity->getIdTouch()] = $touchTransfer;
        }

        return $items;
    }
}
