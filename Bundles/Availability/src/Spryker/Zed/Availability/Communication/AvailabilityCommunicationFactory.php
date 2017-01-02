<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication;

use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Communication\Form\AvailabilityStockForm;
use Spryker\Zed\Availability\Communication\Form\DataProvider\AvailabilityStockFormDataProvider;
use Spryker\Zed\Availability\Communication\Table\AvailabilityAbstractTable;
use Spryker\Zed\Availability\Communication\Table\AvailabilityTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer getQueryContainer()
 */
class AvailabilityCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param int $idLocale
     *
     * @return \Spryker\Zed\Availability\Communication\Table\AvailabilityAbstractTable
     */
    public function createAvailabilityAbstractTable($idLocale)
    {
        $queryProductAbstractAvailability = $this->getQueryContainer()
            ->queryAvailabilityAbstractWithStockByIdLocale($idLocale);

        return new AvailabilityAbstractTable($queryProductAbstractAvailability);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Spryker\Zed\Availability\Communication\Table\AvailabilityTable
     */
    public function createAvailabilityTable($idProductAbstract, $idLocale)
    {
        $queryProductAbstractAvailability = $this->getQueryContainer()
            ->queryAvailabilityWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);

        return new AvailabilityTable($queryProductAbstractAvailability, $idProductAbstract);
    }

    /**
     * @param int $idProduct
     * @param string $sku
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAvailabilityStockForm($idProduct, $sku)
    {
        $availabilityForm = new AvailabilityStockForm();

        $availabilityStockFormDataProvider = $this->createAvailabilityStockFormDataProvider();

        return $this->getFormFactory()->create(
            $availabilityForm,
            $availabilityStockFormDataProvider->getData($idProduct, $sku),
            [
                AvailabilityStockFormDataProvider::DATA_CLASS => $availabilityStockFormDataProvider->getOptions()[AvailabilityStockFormDataProvider::DATA_CLASS],
            ]
        );
    }

    /**
     * @return \Spryker\Zed\Availability\Communication\Form\DataProvider\AvailabilityStockFormDataProvider
     */
    public function createAvailabilityStockFormDataProvider()
    {
        return new AvailabilityStockFormDataProvider($this->getStockFacade());
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToLocaleInterface
     */
    public function getLocalFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface
     */
    public function getStockFacade()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_STOCK);
    }

}
