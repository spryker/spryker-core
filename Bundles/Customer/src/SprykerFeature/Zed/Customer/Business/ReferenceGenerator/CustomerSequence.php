<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\ReferenceGenerator;

use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerNumberSequence;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerNumberSequenceQuery;

class CustomerSequence implements CustomerSequenceInterface
{

    const SEQUENCE_NAME = 'CustomerReferenceGenerator';

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
            $idCurrent = $this->createReferenceNumber();
            if ($idCurrent !== null) {
                $gotNoOrderNumber = false;
            }
        }

        return sprintf('%s', $idCurrent);
    }

    /**
     * @return int
     */
    protected function createReferenceNumber()
    {
        $idCurrent = null;
        $transaction = Propel::getConnection();

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
     * @return SpyCustomerNumberSequence
     */
    protected function getSequence($transaction)
    {
        $sequence = SpyCustomerNumberSequenceQuery::create()
            ->findOneByName(self::SEQUENCE_NAME, $transaction);

        if ($sequence === null) {
            $sequence = new SpyCustomerNumberSequence();
            $sequence->setName(self::SEQUENCE_NAME);
            $sequence->setCurrentId($this->minimumOrderNumber);
        }

        return $sequence;
    }
}
