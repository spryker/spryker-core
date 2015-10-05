<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Business\Model;

use DateTime;
use Generated\Shared\Transfer\TouchTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;

class Touch implements TouchInterface
{

    const BULK_UPDATE_CHUNK_SIZE = 250;

    /**
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param TouchQueryContainerInterface $queryContainer
     * @param ConnectionInterface $connection
     */
    public function __construct(TouchQueryContainerInterface $queryContainer, ConnectionInterface $connection)
    {
        $this->touchQueryContainer = $queryContainer;
        $this->connection = $connection;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkUpdateTouchRecords($itemType, $itemEvent, array $itemIds = [])
    {
        $updated = 0;
        $itemIdChunks = array_chunk($itemIds, self::BULK_UPDATE_CHUNK_SIZE);
        foreach ($itemIdChunks as $itemIdChunk) {
            $touchQuery = $this->touchQueryContainer->queryTouchEntries($itemType, $itemEvent, $itemIdChunk);
            $updated += $touchQuery->update(['Touched' => new DateTime()]);
        }

        return $updated;
    }

    /**
     * @param string $itemType
     *
     * @return TouchTransfer[]
     */
    public function getItemsByType($itemType)
    {
        $entityList = $this->touchQueryContainer
            ->queryTouchListByItemType($itemType)
            ->find()
        ;

        $items = [];
        foreach ($entityList as $entity) {
            $touchTransfer = (new TouchTransfer())
                ->fromArray($entity->toArray())
            ;

            $items[$entity->getIdTouch()] = $touchTransfer;
        }

        return $items;
    }

}
