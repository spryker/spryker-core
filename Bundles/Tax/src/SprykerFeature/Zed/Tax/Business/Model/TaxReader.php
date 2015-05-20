<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;
use SprykerFeature\Zed\Tax\TaxConfig;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSet;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRate;
use Propel\Runtime\Exception\PropelException;

class TaxReader implements TaxReaderInterface
{

    const TAX_SET_UNKNOWN = 'tax set unknown: ';
    const TAX_RATE_UNKNOWN = 'tax rate unknown: ';

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
     *
     * @return TaxRateTransfer
     * @throws PropelException
     * @throws \Exception
     */
    public function getTaxRate($id)
    {
        $taxRateEntity = $this->queryContainer->queryTaxRate($id)->findOne();

        if (null == $taxRateEntity) {
            throw new \Exception(self::TAX_RATE_UNKNOWN . $id);
        }

        return (new TaxRateTransfer)->fromArray($taxRateEntity->toArray());
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws PropelException
     */
    public function taxRateExists($id)
    {
        $result = $this->queryContainer->queryTaxRate($id)->find();

        return $result->isEmpty() ? false : true;
    }

    /**
     * @param int $id
     *
     * @return TaxSetTransfer
     * @throws PropelException
     * @throws \Exception
     */
    public function getTaxSet($id)
    {
        $taxSetEntity = $this->queryContainer->queryTaxSet($id)->findOne();

        if (null == $taxSetEntity) {
            throw new \Exception(self::TAX_SET_UNKNOWN . $id);
        }

        $taxSetTransfer = (new TaxSetTransfer)->fromArray($taxSetEntity->toArray());
        foreach($taxSetEntity->getSpyTaxRates() as $taxRateEntity) {
            $taxRateTransfer = (new TaxRateTransfer)->fromArray($taxRateEntity->toArray());
            $taxSetTransfer->addTaxRate($taxRateTransfer);
        }

        return $taxSetTransfer;
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws PropelException
     */
    public function taxSetExists($id)
    {
        $result = $this->queryContainer->queryTaxSet($id)->find();

        return $result->isEmpty() ? false : true;
    }
}