<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;
use SprykerFeature\Zed\Tax\Business\Model\TaxReaderInterface;
use SprykerFeature\Zed\Tax\TaxConfig;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

class TaxWriter implements TaxWriterInterface {

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
     * @param TaxQueryContainer $queryContainer
     * @param TaxReaderInterface $reader
     * @param TaxConfig $taxSettings
     */
    public function __construct(
        TaxQueryContainer $queryContainer,
        TaxReaderInterface $reader,
        TaxConfig $taxSettings
    ) {
        $this->queryContainer = $queryContainer;
        $this->reader = $reader;
        $this->taxSettings = $taxSettings;
    }

    /**
     * @param TaxRateTransfer $taxRate
     * @return TaxRateTransfer
     * @throws PropelException
     */
    public function createTaxRate(TaxRateTransfer $taxRate) {
        // ...
    }

    /**
     * @param TaxSetTransfer $taxSet
     * @return TaxSetTransfer
     * @throws PropelException
     */
    public function createTaxSet(TaxSetTransfer $taxSet) {
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