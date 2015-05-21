<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;
use SprykerFeature\Zed\Tax\TaxConfig;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

class TaxWriter implements TaxWriterInterface
{

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var TaxQueryContainer
     */
    protected $queryContainer;

    /**
     * @var TaxConfig
     */
    protected $taxSettings;

    /**
     * @param LocatorLocatorInterface $locator
     * @param TaxQueryContainer $queryContainer
     * @param TaxConfig $taxSettings
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        TaxQueryContainer $queryContainer,
        TaxConfig $taxSettings
    ) {
        $this->locator = $locator;
        $this->queryContainer = $queryContainer;
        $this->taxSettings = $taxSettings;
    }

    /**
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @return int
     * @throws PropelException
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        $taxRateEntity = $this->createTaxRateEntity($taxRateTransfer);

        return $taxRateEntity->getIdTaxRate();
    }

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @return int
     * @throws PropelException
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        $taxSetEntity = $this->locator->tax()->entitySpyTaxSet();
        $taxSetEntity->setName($taxSetTransfer->getName());

        foreach($taxSetTransfer->getTaxRates() as $taxRateTransfer) {

            $taxRateEntity = $this->queryContainer->queryTaxRate($taxRateTransfer->getIdTaxRate())->findOne();

            if (!$taxRateEntity) {
                $taxRateEntity = $this->createTaxRateEntity($taxRateTransfer);
            }

            $taxSetEntity->addSpyTaxRate($taxRateEntity);
        }

        $taxSetEntity->save();

        return $taxSetEntity->getIdTaxSet();
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     */
    public function deleteTaxRate($id)
    {
        $taxRateEntity = $this->queryContainer->queryTaxRate($id)->findOne();

        if ($taxRateEntity) {
            $taxRateEntity->delete();
        }
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     */
    public function deleteTaxSet($id)
    {
        $taxSetEntity = $this->queryContainer->queryTaxSet($id)->findOne();

        if ($taxSetEntity) {
            $taxSetEntity->delete();
        }
    }

    private function createTaxRateEntity(TaxRateTransfer $taxRateTransfer)
    {
        $taxRateEntity = $this->locator->tax()->entitySpyTaxRate();
        $taxRateEntity->fromArray($taxRateTransfer->toArray());
        $taxRateEntity->save();

        return $taxRateEntity;
    }
}
