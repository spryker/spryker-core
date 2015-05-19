<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSet;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRate;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;
use SprykerFeature\Zed\Tax\Business\Model\TaxReaderInterface;
use SprykerFeature\Zed\Tax\TaxConfig;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

class TaxWriter implements TaxWriterInterface {

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var TaxQueryContainer
     */
    protected $queryContainer;

    /**
     * @var TaxReaderInterface
     */
    protected $reader;

    /**
     * @var TaxConfig
     */
    protected $taxSettings;

    /**
     * @param LocatorLocatorInterface $locator
     * @param TaxQueryContainer $queryContainer
     * @param TaxReaderInterface $reader
     * @param TaxConfig $taxSettings
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        TaxQueryContainer $queryContainer,
        TaxReaderInterface $reader,
        TaxConfig $taxSettings
    ) {
        $this->locator = $locator;
        $this->queryContainer = $queryContainer;
        $this->reader = $reader;
        $this->taxSettings = $taxSettings;
    }

    /**
     * @param TaxRateTransfer $taxRate
     * @return SpyTaxRate
     * @throws PropelException
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        $taxEntity = $this->locator->tax()->entitySpyTaxRate();
        $taxEntity->fromArray($taxRateTransfer->toArray());
        $taxEntity->save();

        return $taxEntity;
    }

    /**
     * @param TaxSetTransfer $taxSet
     * @return SpyTaxSet
     * @throws PropelException
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer) {
        // ...
    }

    /**
     * @param int $id
     * @throws PropelException
     */
    public function deleteTaxRate($id) {
        // ...
    }

    /**
     * @param int $id
     * @throws PropelException
     */
    public function deleteTaxSet($id) {
        // ...
    }
}