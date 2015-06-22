<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemType;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

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
     * @param $queueName
     *
     * @return int
     */
    public function create($queueName)
    {
        $distribution = new SpyDistributorItemType();
        $distribution->setKey($queueName);
        $distribution->save();

        return $distribution->getIdDistributorItemType();
    }

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @throws PropelException
     * @return int
     */
    public function update($typeKey, $timestamp)
    {
        $distribution = $this->queryContainer
            ->queryTypeByKey($typeKey)
            ->findOne()
        ;

        if (empty($distribution)) {
            throw new Exception;
        }
        $distribution->setLastDistribution($timestamp);
        $distribution->save();

        return $distribution->getIdDistributorItemType();
    }
}
