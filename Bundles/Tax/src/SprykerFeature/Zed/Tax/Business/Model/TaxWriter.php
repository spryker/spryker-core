<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Tax\Business\Model\Exception\MissingTaxRateException;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;
use SprykerFeature\Zed\Tax\TaxConfig;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;
use Propel\Runtime\Collection\Collection;

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
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        $taxRateEntity = $this->queryContainer->queryTaxRate($taxRateTransfer->getIdTaxRate())->findOne();

        if (null == $taxRateEntity) {
            throw new ResourceNotFoundException();
        }

        $taxRateEntity->fromArray($taxRateTransfer->toArray());
        $taxRateEntity->save();
    }

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        $taxSetEntity = $this->locator->tax()->entitySpyTaxSet();
        $taxSetEntity->setName($taxSetTransfer->getName());

        if (0 === $taxSetTransfer->getTaxRates()->count()) {
            throw new MissingTaxRateException();
        }

        foreach($taxSetTransfer->getTaxRates() as $taxRateTransfer) {
            $taxRateEntity = $this->findOrCreateTaxRateEntity($taxRateTransfer);
            $taxSetEntity->addSpyTaxRate($taxRateEntity);
        }

        $taxSetEntity->save();

        return $taxSetEntity->getIdTaxSet();
    }

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        $taxSetEntity = $this->queryContainer->queryTaxSet($taxSetTransfer->getIdTaxSet())->findOne();

        if (null == $taxSetEntity) {
            throw new ResourceNotFoundException();
        }

        if (0 === $taxSetTransfer->getTaxRates()->count()) {
            throw new MissingTaxRateException();
        }

        $taxSetEntity->setName($taxSetTransfer->getName())->setSpyTaxRates(new Collection());

        foreach($taxSetTransfer->getTaxRates() as $taxRateTransfer) {
            $taxRateEntity = $this->findOrCreateTaxRateEntity($taxRateTransfer);
            $taxSetEntity->addSpyTaxRate($taxRateEntity);
        }

        $taxSetEntity->save();
    }

    /**
     * @param int $taxSetId
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     */
    public function addTaxRateToTaxSet($taxSetId, TaxRateTransfer $taxRateTransfer)
    {
        $taxSetEntity = $this->queryContainer->queryTaxSet($taxSetId)->findOne();

        if (!$taxSetEntity) {
            throw new ResourceNotFoundException();
        }

        $taxRate = $this->queryContainer->queryTaxRate($taxRateTransfer->getIdTaxRate())->findOne();
        if ($taxSetEntity->getSpyTaxRates()->contains($taxRate)) {
            return;
        }

        $taxRateEntity = $this->findOrCreateTaxRateEntity($taxRateTransfer);
        $taxSetEntity->addSpyTaxRate($taxRateEntity);
        $taxSetEntity->save();
    }


    /**
     * @param int $taxSetId
     * @param int $taxRateId
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function removeTaxRateFromTaxSet($taxSetId, $taxRateId)
    {
        $taxSetEntity = $this->queryContainer->queryTaxSet($taxSetId)->findOne();

        if (!$taxSetEntity) {
            throw new ResourceNotFoundException();
        }

        $taxRate = $this->queryContainer->queryTaxRate($taxRateId)->findOne();

        if (!$taxSetEntity->getSpyTaxRates()->contains($taxRate)) {
            return;
        }

        if (1 === $taxSetEntity->getSpyTaxRates()->count()) {
            throw new MissingTaxRateException();
        }

        $taxSetEntity->removeSpyTaxRate($taxRate);
        $taxSetEntity->save();
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

    private function findOrCreateTaxRateEntity(TaxRateTransfer $taxRateTransfer)
    {
        if (!empty($taxRateTransfer->getIdTaxRate())) {
            $taxRateEntity = $this->queryContainer->queryTaxRate($taxRateTransfer->getIdTaxRate())->findOne();
            if (!$taxRateEntity) {
                throw new ResourceNotFoundException();
            }
        } else {
            $taxRateEntity = $this->createTaxRateEntity($taxRateTransfer);
        }

        return $taxRateEntity;
    }
}
