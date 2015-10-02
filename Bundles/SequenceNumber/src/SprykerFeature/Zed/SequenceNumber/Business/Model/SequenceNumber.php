<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business\Model;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
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

    /**
     * @param RandomNumberGeneratorInterface $randomNumberGenerator
     * @param SequenceNumberSettingsInterface $sequenceNumberSettings
     */
    public function __construct(RandomNumberGeneratorInterface $randomNumberGenerator, SequenceNumberSettingsInterface $sequenceNumberSettings)
    {
        $this->randomNumberGenerator = $randomNumberGenerator;
        $this->sequenceNumberSettings = $sequenceNumberSettings;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $idCurrent = $this->createNumber();
        if ($idCurrent < 1) {
            throw new \Exception('X');
        }

        $padding = $this->sequenceNumberSettings->getPadding();
        if ($padding > 0) {
            $number = sprintf('%1$0' . $padding . 'd', $idCurrent);
        } else {
            $number = sprintf('%s', $idCurrent);
        }

        return $this->sequenceNumberSettings->getPrefix() . $number;
    }

    /**
     * @return int
     */
    protected function createNumber()
    {
        $idCurrent = 0;
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
            $idCurrent = 0;
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
