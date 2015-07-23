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

    /**
     * @var int
     */
    protected $minimumCustomerNumber;

    /**
     * @var RandomNumberGenerator
     */
    protected $randomNumberGenerator;

    /**
     * @param RandomNumberGeneratorInterface $randomNumberGenerator
     * @param int $minimumCustomerNumber
     */
    public function __construct(RandomNumberGeneratorInterface $randomNumberGenerator, $minimumCustomerNumber)
    {
        $this->randomNumberGenerator = $randomNumberGenerator;
        $this->minimumCustomerNumber = $minimumCustomerNumber;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $idCurrent = null;
        $gotCustomerNumber = false;

        while (!$gotCustomerNumber) {
            $idCurrent = $this->createReferenceNumber();
            if ($idCurrent !== null) {
                $gotCustomerNumber = true;
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
    protected function getSequence(ConnectionInterface $transaction)
    {
        $sequence = SpyCustomerNumberSequenceQuery::create()
            ->findOneByName(self::SEQUENCE_NAME, $transaction);

        if ($sequence === null) {
            $sequence = new SpyCustomerNumberSequence();
            $sequence->setName(self::SEQUENCE_NAME);
            $sequence->setCurrentId($this->minimumCustomerNumber);
        }

        return $sequence;
    }
}
