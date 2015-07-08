<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Propel;
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
        return sprintf('%s-%s-%s-%s',
            $this->getEnvironment(),
            $this->getStore(),
            $this->getOrderNumber(),
            $this->getTimestamp()
        );
    }

    /**
     * @return string
     */
    protected function getOrderNumber()
    {
        $transaction = Propel::getWriteConnection(SpySalesOrderNumberSequenceTableMap::DATABASE_NAME);
        $gotNoOrderNumber = true;

        while ($gotNoOrderNumber) {
            try {
                $transaction->beginTransaction();

                $sequence = SpySalesOrderNumberSequenceQuery::create()
                    ->findOneByName(self::SEQUENCE_NAME, $transaction);

                if ($sequence === null) {
                    $sequence = new SpySalesOrderNumberSequence();
                    $sequence->setName(self::SEQUENCE_NAME);
                    $current_id = $this->minimumOrderNumber;
                } else {
                    $current_id = $sequence->getCurrentId();
                }

                $current_id += rand($this->orderNumberIncrementMin, $this->orderNumberIncrementMax);

                $sequence->setCurrentId($current_id);
                $sequence->save($transaction);

                $transaction->commit();
                $gotNoOrderNumber = false;
            } catch (\Exception $e) {
                $transaction->rollback();
            }
        }

        return sprintf('%s', $current_id);
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
