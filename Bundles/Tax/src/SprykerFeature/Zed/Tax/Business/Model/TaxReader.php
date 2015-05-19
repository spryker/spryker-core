<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;
use Sprykerfeature\Zed\Tax\TaxConfig;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

class TaxReader implements TaxReaderInterface {

    /**
     * @var TaxQueryContainer
     */
    protected $queryContainer;

    /**
     * @var TaxConfig
     */
    protected $taxSettings;

    /**
     * @param TaxQueryContainer $queryContainer
     * @param TaxConfig $taxSettings
     */
    public function __construct(
        TaxQueryContainer $queryContainer,
        TaxConfig $taxSettings
    ) {
        $this->queryContainer = $queryContainer;
        $this->taxSettings = $taxSettings;
    }

    /**
     * @param int $id
     * @return TaxRateTransfer
     * @throws PropelException
     */
    public function getTaxRate($id) {
        // ...
    }

    /**
     * @param int $id
     * @return TaxSetTransfer
     * @throws PropelException
     */
    public function getTaxSet($id) {
        // ...
    }
}