<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Tax\Business\Model;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Tax\Business\Model\Exception\MissingTaxRateException;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainerInterface;
use SprykerFeature\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;
use SprykerFeature\Zed\Tax\TaxConfig;
use SprykerFeature\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Collection\Collection;

class TaxWriter implements TaxWriterInterface
{

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var TaxQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var TaxConfig
     */
    protected $taxSettings;

    /**
     * @var TaxChangePluginInterface[]
     */
    protected $taxChangePlugins;

    /**
     * @param LocatorLocatorInterface $locator
     * @param TaxQueryContainerInterface $queryContainer
     * @param TaxChangePluginInterface[] $taxChangePlugins
     */
    public function __construct(
        LocatorLocatorInterface $locator,
        TaxQueryContainerInterface $queryContainer,
        array $taxChangePlugins
    ) {
        $this->locator = $locator;
        $this->queryContainer = $queryContainer;
        $this->taxChangePlugins = $taxChangePlugins;
    }

    /**
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @throws PropelException
     *
     * @return TaxRateTransfer
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        $taxRateEntity = $this->createTaxRateEntity($taxRateTransfer);

        $taxRateTransfer->setIdTaxRate($taxRateEntity->getIdTaxRate());

        return $taxRateTransfer;
    }

    /**
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return int
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        $taxRateEntity = $this->queryContainer->queryTaxRate($taxRateTransfer->getIdTaxRate())->findOne();

        if (null === $taxRateEntity) {
            throw new ResourceNotFoundException();
        }

        $taxRateEntity->fromArray($taxRateTransfer->toArray());

        foreach ($this->taxChangePlugins as $plugin) {
            $plugin->handleTaxRateChange($taxRateEntity->getIdTaxRate());
        }

        return $taxRateEntity->save();
    }

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     *
     * @return TaxSetTransfer
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        $taxSetEntity = $this->locator->tax()->entitySpyTaxSet();
        $taxSetEntity->setName($taxSetTransfer->getName());

        if (0 === $taxSetTransfer->getTaxRates()->count()) {
            throw new MissingTaxRateException();
        }

        foreach ($taxSetTransfer->getTaxRates() as $taxRateTransfer) {
            $taxRateEntity = $this->findOrCreateTaxRateEntity($taxRateTransfer);
            $taxSetEntity->addSpyTaxRate($taxRateEntity);
        }

        $taxSetEntity->save();

        $taxSetTransfer->setIdTaxSet($taxSetEntity->getIdTaxSet());

        return $taxSetTransfer;
    }

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     *
     * @return int
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        $taxSetEntity = $this->queryContainer->queryTaxSet($taxSetTransfer->getIdTaxSet())->findOne();

        if (null === $taxSetEntity) {
            throw new ResourceNotFoundException();
        }

        if (0 === $taxSetTransfer->getTaxRates()->count()) {
            throw new MissingTaxRateException();
        }

        $taxSetEntity->setName($taxSetTransfer->getName())->setSpyTaxRates(new Collection());

        foreach ($taxSetTransfer->getTaxRates() as $taxRateTransfer) {
            $taxRateEntity = $this->findOrCreateTaxRateEntity($taxRateTransfer);
            $taxSetEntity->addSpyTaxRate($taxRateEntity);
        }

        foreach ($this->taxChangePlugins as $plugin) {
            $plugin->handleTaxSetChange($taxSetEntity->getIdTaxSet());
        }

        return $taxSetEntity->save();
    }

    /**
     * @param int $taxSetId
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return int
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

        foreach ($this->taxChangePlugins as $plugin) {
            $plugin->handleTaxSetChange($taxSetEntity->getIdTaxSet());
        }

        return $taxSetEntity->save();
    }

    /**
     * @param int $taxSetId
     * @param int $taxRateId
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     *
     * @return int
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

        foreach ($this->taxChangePlugins as $plugin) {
            $plugin->handleTaxSetChange($taxSetEntity->getIdTaxSet());
        }

        return $taxSetEntity->save();
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
