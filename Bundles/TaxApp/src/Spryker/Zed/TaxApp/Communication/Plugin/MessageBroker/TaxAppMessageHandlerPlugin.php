<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\ConfigureTaxAppTransfer;
use Generated\Shared\Transfer\DeleteTaxAppTransfer;
use Generated\Shared\Transfer\TaxAppConfigConditionsTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\TaxApp\Business\TaxAppFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxApp\Communication\TaxAppCommunicationFactory getFactory()
 * @method \Spryker\Zed\TaxApp\TaxAppConfig getConfig()
 */
class TaxAppMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigureTaxAppTransfer $configureTaxAppTransfer
     *
     * @return void
     */
    public function onTaxAppConfigured(ConfigureTaxAppTransfer $configureTaxAppTransfer): void
    {
        $taxAppConfigTransfer = (new TaxAppConfigTransfer())
            ->setApplicationid($configureTaxAppTransfer->getMessageAttributesOrFail()->getEmitterOrFail())
            ->setApiUrl($configureTaxAppTransfer->getApiUrlOrFail())
            ->setIsActive($configureTaxAppTransfer->getIsActiveOrFail())
            ->setVendorCode($configureTaxAppTransfer->getVendorCodeOrFail())
            ->setStoreReference($configureTaxAppTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail());

        $this->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DeleteTaxAppTransfer $deleteTaxAppTransfer
     *
     * @return void
     */
    public function onTaxAppDeleted(DeleteTaxAppTransfer $deleteTaxAppTransfer): void
    {
        $taxAppConditionsTransfer = (new TaxAppConfigConditionsTransfer());
        $taxAppConditionsTransfer->addStoreReference($deleteTaxAppTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail())->addVendorCode($deleteTaxAppTransfer->getVendorCodeOrFail());

        $taxAppConfigCriteria = (new TaxAppConfigCriteriaTransfer())->setTaxAppConfigConditions($taxAppConditionsTransfer);

        $this->getFacade()->deleteTaxAppConfig($taxAppConfigCriteria);
    }

    /**
     * {@inheritDoc}
     * - Adds new tax app endpoint to the config for the store.
     * - Returns an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        return [
            ConfigureTaxAppTransfer::class => [$this, 'onTaxAppConfigured'],
            DeleteTaxAppTransfer::class => [$this, 'onTaxAppDeleted'],
        ];
    }
}
