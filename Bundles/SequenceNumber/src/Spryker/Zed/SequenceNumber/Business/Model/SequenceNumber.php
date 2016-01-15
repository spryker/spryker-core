<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SequenceNumber\Business\Model;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\SequenceNumber\Business\Exception\InvalidSequenceNumberException;
use Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;
use Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface;
use Orm\Zed\SequenceNumber\Persistence\SpySequenceNumber;
use Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery;

class SequenceNumber implements SequenceNumberInterface
{

    /** @var RandomNumberGenerator */
    protected $randomNumberGenerator;

    /** @var SequenceNumberSettingsTransfer */
    protected $sequenceNumberSettings;

    /** @var ConnectionInterface */
    protected $connection;

    /**
     * @param RandomNumberGeneratorInterface $randomNumberGenerator
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettings
     * @param ConnectionInterface $connection
     */
    public function __construct(RandomNumberGeneratorInterface $randomNumberGenerator, SequenceNumberSettingsTransfer $sequenceNumberSettings, ConnectionInterface $connection)
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
            $sequence = $this->getSequence();
            $idCurrent = $sequence->getCurrentId() + $this->randomNumberGenerator->generate();

            $sequence->setCurrentId($idCurrent);
            $sequence->save();

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();

            throw new InvalidSequenceNumberException(
                'Could not generate sequence number. Make sure your settings are complete. Error: ' . $e->getMessage());
        }

        return $idCurrent;
    }

    /**
     * @return SpySequenceNumber
     */
    protected function getSequence()
    {
        $sequence = SpySequenceNumberQuery::create()
            ->findOneByName($this->sequenceNumberSettings->getName());

        if ($sequence === null) {
            $sequence = new SpySequenceNumber();
            $sequence->setName($this->sequenceNumberSettings->getName());
            $sequence->setCurrentId($this->sequenceNumberSettings->getMinimumNumber());

            return $sequence;
        }

        $expectedCurrentValue = $this->sequenceNumberSettings->getMinimumNumber() - 1;

        $current = $sequence->getCurrentId();
        if ($current < $expectedCurrentValue) {
            $sequence->setCurrentId($expectedCurrentValue);
        }

        return $sequence;
    }

}
