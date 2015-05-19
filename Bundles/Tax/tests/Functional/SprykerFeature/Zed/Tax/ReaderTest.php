<?php

namespace Functional\SprykerFeature\Zed\Tax;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Tax\Business\TaxFacade;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRateQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;

/**
 * @group TaxTest
 */
class WriterTest extends Test
{
    /**
     * @var TaxFacade
     */
    private $taxFacade;

    /**
     * @var AutoCompletion $locator
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->taxFacade = new TaxFacade(new Factory('Tax'), $this->locator);
        $this->setTestData();
    }

    public function testGetTaxRate()
    {
        // ...
    }

    public function testSetTaxRate()
    {
        // ...
    }

    private function setTestData()
    {
        // ...
    }
}