<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Touch\Business\Model;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;

class TouchRecord implements TouchRecordInterface
{

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
            ->setTouched(new \DateTime());

        $touchEntity->save();

        return true;
    }

}
