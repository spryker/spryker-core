<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderNumberSequenceTableMap;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNumberSequence;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNumberSequenceQuery;

class OrderSequence implements OrderSequenceInterface
{

    const SEQUENCE_NAME = 'OrderReferenceGenerator';

    /** @var int */
    protected $minimumOrderNumber;

    /** @var RandomNumberGenerator */
    protected $randomNumberGenerator;

    public function __construct(RandomNumberGeneratorInterface $randomNumberGenerator, $minimumOrderNumber)
    {
        $this->randomNumberGenerator = $randomNumberGenerator;
        $this->minimumOrderNumber = $minimumOrderNumber;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $idCurrent = null;
        $gotNoOrderNumber = true;

        while ($gotNoOrderNumber) {
            $idCurrent = $this->createOrderNumber();
            if($idCurrent !== null) {
                $gotNoOrderNumber = false;
            }
        }

        return sprintf('%s', $idCurrent);
    }

    /**
     * @return int
     */
    protected function createOrderNumber()
    {
        $idCurrent = null;
        $transaction = Propel::getConnection(SpySalesOrderNumberSequenceTableMap::DATABASE_NAME);

        try {
            $transaction->beginTransaction();

            $sequence = $this->getSequence($transaction);
            $idCurrent = $sequence->getCurrentId() + $this->randomNumberGenerator->generate();

            $sequence->setCurrentId($idCurrent);
            $sequence->save($transaction);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            $idCurrent = null;
        }

        return $idCurrent;
    }

    /**
     * @param ConnectionInterface $transaction
     *
     * @return SpySalesOrderNumberSequence
     */
    protected function getSequence($transaction)
    {
        $sequence = SpySalesOrderNumberSequenceQuery::create()
            ->findOneByName(self::SEQUENCE_NAME, $transaction);

        if ($sequence === null) {
            $sequence = new SpySalesOrderNumberSequence();
            $sequence->setName(self::SEQUENCE_NAME);
            $sequence->setCurrentId($this->minimumOrderNumber);
        }

        return $sequence;
    }
}
