<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model;

use Generated\Shared\Transfer\TouchTransfer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

class Touch implements TouchInterface
{
    public const BULK_UPDATE_CHUNK_SIZE = 250;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     */
    public function __construct(TouchQueryContainerInterface $queryContainer)
    {
        $this->touchQueryContainer = $queryContainer;
    }

    /**
     * @param string $itemType
     *
     * @return \Generated\Shared\Transfer\TouchTransfer[]
     */
    public function getItemsByType($itemType)
    {
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
