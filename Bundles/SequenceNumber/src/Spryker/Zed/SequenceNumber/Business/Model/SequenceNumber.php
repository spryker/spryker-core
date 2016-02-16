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

    /** @var \Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator */
    protected $randomNumberGenerator;

    /** @var \Generated\Shared\Transfer\SequenceNumberSettingsTransfer */
    protected $sequenceNumberSettings;

    /** @var \Propel\Runtime\Connection\ConnectionInterface */
    protected $connection;

    /**
     * @param \Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface $randomNumberGenerator
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer $sequenceNumberSettings
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     */
    public function __construct(RandomNumberGeneratorInterface $randomNumberGenerator, SequenceNumberSettingsTransfer $sequenceNumberSettings, ConnectionInterface $connection)
    {
        $this->randomNumberGenerator = $randomNumberGenerator;
        $this->sequenceNumberSettings = $sequenceNumberSettings;
        $this->connection = $connection;
    }

    /**
     * @throws \Spryker\Zed\SequenceNumber\Business\Exception\InvalidSequenceNumberException
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
     * @throws \Spryker\Zed\SequenceNumber\Business\Exception\InvalidSequenceNumberException
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
                'Could not generate sequence number. Make sure your settings are complete. Error: ' . $e->getMessage()
            );
        }

        return $idCurrent;
    }

    /**
     * @return \Orm\Zed\SequenceNumber\Persistence\SpySequenceNumber
     */
    protected function getSequence()
    {
        $sequence = SpySequenceNumberQuery::create()
            ->findOneByName($this->sequenceNumberSettings->getName());

        $offset = $this->sequenceNumberSettings->getOffset();
        if (!$offset) {
            $offset = $this->sequenceNumberSettings->getMinimumNumber();
        }

        if ($sequence === null) {
            $sequence = new SpySequenceNumber();
            $sequence->setName($this->sequenceNumberSettings->getName());
            $sequence->setCurrentId($offset);

            return $sequence;
        }

        $expectedCurrentValue = $offset - 1;

        $current = $sequence->getCurrentId();
        if ($current < $expectedCurrentValue) {
            $sequence->setCurrentId($expectedCurrentValue);
        }

        return $sequence;
    }

}
