<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Business\Model;

use DateTime;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouch;
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
     * @param bool $keyChange
     *
     * @throws \Exception
     * @throws PropelException
     *
     * @return bool
     */
    public function saveTouchRecord($itemType, $itemEvent, $idItem, $keyChange = false)
    {

//        Propel::getConnection()
//            ->beginTransaction()
//        ;
        //TODO clean query and move it to QueryContainer
        $touchQuery = $this->touchQueryContainer->queryTouchEntry($itemType, $idItem);
        $touchKeyChangeQuery = clone $touchQuery;

        if ($keyChange) {
            $touchKeyChangeQuery->setQueryKey('keyChange');
            $touchKeyChangeQuery = $touchKeyChangeQuery->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
            $touchOldEntity = $touchKeyChangeQuery->findOne();


            //TODO clean query and move it to QueryContainer
            if (null !== $touchOldEntity) {
                $touchDeletedQuery = clone $touchQuery;
                $touchDeletedQuery->setQueryKey('deleteQuery');
                $touchDeletedQuery ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED);
                $touchDeletedEntity = $touchDeletedQuery->findOne();


                if (null === $touchDeletedEntity) {

                    // TODO make it clean
                    $touchOldEntity
                        ->setItemType($itemType)
                        ->setItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
                        ->setItemId($idItem)
                        ->setTouched(new DateTime());
                    $touchOldEntity->save();
                }
            }
        }

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry($itemType, $idItem, SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $touchEntity = $touchQuery->findOneOrCreate();

        $touchEntity
            ->setItemType($itemType)
            ->setItemEvent($itemEvent)
            ->setItemId($idItem)
            ->setTouched(new DateTime());

        $touchEntity->save();

//        Propel::getConnection()
//            ->commit()
//        ;

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
