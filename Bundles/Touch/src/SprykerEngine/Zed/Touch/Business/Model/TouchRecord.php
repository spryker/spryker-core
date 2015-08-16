<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Business\Model;

use DateTime;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;

class TouchRecord implements TouchRecordInterface
{
    const BULK_UPDATE_CHUNK_SIZE = 250;

    /**
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @param TouchQueryContainerInterface $queryContainer
     */
    public function __construct(TouchQueryContainerInterface $queryContainer)
    {
        $this->touchQueryContainer = $queryContainer;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param int $idItem
     *
     * @throws \Exception
     * @throws PropelException
     *
     * @return bool
     */
    public function saveTouchRecord($itemType, $itemEvent, $idItem)
    {
        $touchQuery = $this->touchQueryContainer->queryTouchEntry($itemType, $idItem);
        $touchEntity = $touchQuery->findOneOrCreate();

        $touchEntity
            ->setItemType($itemType)
            ->setItemEvent($itemEvent)
            ->setItemId($idItem)
            ->setTouched(new DateTime());

        $touchEntity->save();

        return true;
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
}
