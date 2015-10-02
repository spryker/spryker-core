<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\SequenceNumber;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;
use SprykerFeature\Zed\SequenceNumber\Persistence\SequenceNumberQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\Factory;

/**
 * @group SequenceNumberTest
 */
class SequenceNumberTest extends Test
{

    /**
     * @var SequenceNumberFacade
     */
    protected $sequenceNumberFacade;

    /**
     * @var SequenceNumberQueryContainer
     */
    protected $sequenceNumberQueryContainer;

    public function setUp()
    {
        parent::setUp();

        $locator = Locator::getInstance();
        $this->sequenceNumberFacade = new SequenceNumberFacade(new \SprykerEngine\Zed\Kernel\Business\Factory('SequenceNumber'), $locator);
        $this->sequenceNumberQueryContainer = new SequenceNumberQueryContainer(new Factory('SequenceNumber'), $locator);
    }

    public function testGenerate()
    {
        $sequenceNumber = $this->sequenceNumberFacade->generate();
        $this->assertEquals(2, $sequenceNumber);
    }

}
