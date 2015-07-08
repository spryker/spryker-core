<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\Sales\Dependency\Plugin\OrderReferenceGeneratorInterface;
use SprykerFeature\Shared\Library\Application\Environment;
use SprykerFeature\Zed\Sales\Persistence\Propel\Map\SpySalesOrderNumberSequenceTableMap;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNumberSequence;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderNumberSequenceQuery;

class OrderReferenceGenerator implements OrderReferenceGeneratorInterface
{

    const SEQUENCE_NAME = 'OrderReferenceGenerator';

    /** @var int */
    protected $minimumOrderNumber;

    /** @var int */
    protected $orderNumberIncrementMin;

    /** @var int */
    protected $orderNumberIncrementMax;

    /**
     * @param int $minimumOrderNumber
     * @param int $orderNumberIncrementMin
     * @param int $orderNumberIncrementMax
     */
    public function __construct($minimumOrderNumber, $orderNumberIncrementMin, $orderNumberIncrementMax)
    {
        $this->minimumOrderNumber = $minimumOrderNumber;
        $this->orderNumberIncrementMin = $orderNumberIncrementMin;
        $this->orderNumberIncrementMax = $orderNumberIncrementMax;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function generateOrderReference(OrderTransfer $orderTransfer)
    {
        $orderReferenceParts = [
            $this->getEnvironment(),
            $this->getStore(),
        ];

        if ($this->isDevelopment()) {
            $orderReferenceParts[] = $this->getTimestamp();
        } else {
            $orderReferenceParts[] = $this->getOrderNumber();
        }

        return implode('-', $orderReferenceParts);
    }

    /**
     * @return string
     */
    protected function getOrderNumber()
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
     * @return string
     */
    protected function createOrderNumber()
    {
        $idCurrent = null;
        $transaction = Propel::getWriteConnection(SpySalesOrderNumberSequenceTableMap::DATABASE_NAME);

        try {
            $transaction->beginTransaction();

            $sequence = $this->getSequence($transaction);
            $idCurrent = $sequence->getCurrentId() + rand($this->orderNumberIncrementMin, $this->orderNumberIncrementMax);

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

    /**
     * @return string
     */
    protected function getEnvironment()
    {
        $env = constant('APPLICATION_ENV');

        if ($env === Environment::ENV_PRODUCTION) {
            return 'P';
        }

        if ($env === Environment::ENV_STAGING) {
            return 'S';
        }

        if ($env === Environment::ENV_DEVELOPMENT) {
            return 'D';
        }

        return 'U';
    }

    /**
     * @return bool
     */
    protected function isDevelopment()
    {
        return (constant('APPLICATION_ENV') === Environment::ENV_DEVELOPMENT);
    }

    /**
     * @return string
     */
    protected function getStore()
    {
        return constant('APPLICATION_STORE');
    }

    /**
     * @return string
     */
    protected function getTimestamp()
    {
        $ts = microtime();
        $ts = str_replace('.', '', $ts);
        $ts = str_replace(' ', '', $ts);

        return $ts;
    }
}
