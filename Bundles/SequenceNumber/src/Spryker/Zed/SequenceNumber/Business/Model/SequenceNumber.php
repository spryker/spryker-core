<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SequenceNumber\Business\Model;

use Exception;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Orm\Zed\SequenceNumber\Persistence\SpySequenceNumber;
use Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\SequenceNumber\Business\Exception\InvalidSequenceNumberException;
use Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface;

class SequenceNumber implements SequenceNumberInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface
     */
    protected $randomNumberGenerator;

    /**
     * @var \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    protected $sequenceNumberSettings;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @see \Spryker\Shared\SequenceNumber\SequenceNumberConstants::LIMIT_LIST
     *
     * @var array
     */
    protected $limitList;

    /**
     * @param \Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface $randomNumberGenerator
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer $sequenceNumberSettings
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     * @param array $limitList
     */
    public function __construct(
        RandomNumberGeneratorInterface $randomNumberGenerator,
        SequenceNumberSettingsTransfer $sequenceNumberSettings,
        ConnectionInterface $connection,
        array $limitList = []
    ) {
        $this->randomNumberGenerator = $randomNumberGenerator;
        $this->sequenceNumberSettings = $sequenceNumberSettings;
        $this->connection = $connection;
        $this->limitList = $limitList;
    }

    /**
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
            $idCurrent = $this->handleDatabaseTransaction(function () {
                return $this->createNumberTransaction();
            });
        } catch (Exception $e) {
            throw new InvalidSequenceNumberException(
                'Could not generate sequence number. Make sure your settings are complete. Error: ' . $e->getMessage()
            );
        }

        return $idCurrent;
    }

    /**
     * @return int
     */
    protected function createNumberTransaction()
    {
        $sequence = $this->getSequence();
        $idCurrent = $sequence->getCurrentId() + $this->randomNumberGenerator->generate();

        $this->assertIdCurrent($idCurrent);

        $sequence->setCurrentId($idCurrent);
        $sequence->save();

        return $idCurrent;
    }

    /**
     * @return \Orm\Zed\SequenceNumber\Persistence\SpySequenceNumber
     */
    protected function getSequence()
    {
        $sequence = SpySequenceNumberQuery::create()
            ->forUpdate(true) // `forUpdate` will lock the row when select query executes
            ->findOneByName($this->sequenceNumberSettings->getName());

        $offset = $this->sequenceNumberSettings->getOffset();

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

    /**
     * @param int $idCurrent
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function assertIdCurrent($idCurrent)
    {
        if (!isset($this->limitList[$this->sequenceNumberSettings->getName()])) {
            return;
        }

        if ($idCurrent > $this->limitList[$this->sequenceNumberSettings->getName()]) {
            throw new Exception(sprintf(
                'The sequence limit is reached for %s',
                $this->sequenceNumberSettings->getName()
            ));
        }
    }
}
