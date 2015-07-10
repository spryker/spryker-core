<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Distributor\Business\Exception\ItemTypeDoesNotExistException;
use SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemType;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;

class ItemTypeWriter implements ItemTypeWriterInterface
{

    /**
     * @var DistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param DistributorQueryContainerInterface $queryContainer
     */
    public function __construct(DistributorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $typeKey
     *
     * @return int
     */
    public function create($typeKey)
    {
        $itemType = new SpyDistributorItemType();
        $itemType->setTypeKey($typeKey);
        $itemType->setLastDistribution(\DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:00'));
        $itemType->save();

        return $itemType->getIdDistributorItemType();
    }

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @throws ItemTypeDoesNotExistException
     * @throws PropelException
     *
     * @return int
     */
    public function update($typeKey, $timestamp)
    {
        $itemType = $this->queryContainer
            ->queryTypeByKey($typeKey)
            ->findOne()
        ;

        if (empty($itemType)) {
            throw new ItemTypeDoesNotExistException(
                sprintf(
                    'Item type %s does not exist',
                    $typeKey
                )
            );
        }

        $itemType->setLastDistribution($timestamp);
        $itemType->save();

        return $itemType->getIdDistributorItemType();
    }

}
