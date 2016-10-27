<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication;

use Generated\Shared\Transfer\AvailabilityStockTransfer;
use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Communication\Form\AvailabilityStockForm;
use Spryker\Zed\Availability\Communication\Form\DataProvider\AvailabilityStockFormDataProvider;
use Spryker\Zed\Availability\Communication\Table\AvailabilityAbstractTable;
use Spryker\Zed\Availability\Communication\Table\AvailabilityTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer
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
        $queryProductAbstractAvailability = $this->getQueryContainer()->queryAvailabilityAbstractWithStockByIdLocale($idLocale);

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
        $queryProductAbstractAvailability = $this->getQueryContainer()->queryAvailabilityWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);

        return new AvailabilityTable($queryProductAbstractAvailability, $idProductAbstract);
    }

    /**
     * @param \Spryker\Zed\Availability\Communication\Form\DataProvider\AvailabilityStockFormDataProvider $availabilityStockFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAvailabilityStockForm(AvailabilityStockFormDataProvider $availabilityStockFormDataProvider)
    {
        $availabilityForm = new AvailabilityStockForm();

        return $this->getFormFactory()->create(
            $availabilityForm,
            $availabilityStockFormDataProvider->getData(),
            [
                'data_class' => AvailabilityStockTransfer::class,
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityStockTransfer
     *
     * @return \Spryker\Zed\Availability\Communication\Form\DataProvider\AvailabilityStockFormDataProvider
     */
    public function createAvailabilityStockFormDataProvider(AvailabilityStockTransfer $availabilityStockTransfer)
    {
        return new AvailabilityStockFormDataProvider($availabilityStockTransfer);
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
