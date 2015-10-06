<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business\Model;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\SequenceNumber\Business\Exception\InvalidSequenceNumberException;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface;
use SprykerFeature\Zed\SequenceNumber\Persistence\Propel\SpySequenceNumber;
use SprykerFeature\Zed\SequenceNumber\Persistence\Propel\SpySequenceNumberQuery;

class SequenceNumber implements SequenceNumberInterface
{

    /** @var RandomNumberGenerator */
    protected $randomNumberGenerator;

    /** @var SequenceNumberSettingsInterface */
    protected $sequenceNumberSettings;

    /** @var ConnectionInterface */
    protected $connection;

    /**
     * @param RandomNumberGeneratorInterface $randomNumberGenerator
     * @param SequenceNumberSettingsInterface $sequenceNumberSettings
     * @param ConnectionInterface $connection
     */
    public function __construct(RandomNumberGeneratorInterface $randomNumberGenerator, SequenceNumberSettingsInterface $sequenceNumberSettings, ConnectionInterface $connection)
    {
        $this->randomNumberGenerator = $randomNumberGenerator;
        $this->sequenceNumberSettings = $sequenceNumberSettings;
        $this->connection = $connection;
    }

    /**
     * @throws InvalidSequenceNumberException
     *
     * @return string
     */
    public function generate()
    {
        $idCurrent = $this->createNumber();

        $padding = $this->sequenceNumberSettings->getPadding();
        if ($padding > 0) {
            $number = sprintf('%1$0' . $padding . 'd', $idCurrent);
        } else {
            $number = sprintf('%s', $idCurrent);
        }

        return $this->sequenceNumberSettings->getPrefix() . $number;
    }

    /**
     * @throws InvalidSequenceNumberException
     *
     * @return int
     */
    protected function createNumber()
    {
        try {
            $this->connection->beginTransaction();

            $sequence = $this->getSequence($this->connection);
            $idCurrent = $sequence->getCurrentId() + $this->randomNumberGenerator->generate();

            $sequence->setCurrentId($idCurrent);
            $sequence->save($this->connection);

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();

            throw new InvalidSequenceNumberException(
                'Could not generate sequence number. Make sure your settings are complete. Error: ' . $e->getMessage());
        }

        return $idCurrent;
    }

    /**
     * @param ConnectionInterface $transaction
     *
     * @return SpySequenceNumber
     */
    protected function getSequence($transaction)
    {
        $sequence = SpySequenceNumberQuery::create()
            ->findOneByName($this->sequenceNumberSettings->getName(), $transaction);

        if ($sequence === null) {
            $sequence = new SpySequenceNumber();
            $sequence->setName($this->sequenceNumberSettings->getName());
            $sequence->setCurrentId($this->sequenceNumberSettings->getMinimumNumber());
        }

        return $sequence;
    }

}
