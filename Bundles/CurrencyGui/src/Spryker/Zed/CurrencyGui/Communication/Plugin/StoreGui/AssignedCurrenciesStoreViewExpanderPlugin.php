<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreViewExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CurrencyGui\Communication\CurrencyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CurrencyGui\CurrencyGuiConfig getConfig()
 */
class AssignedCurrenciesStoreViewExpanderPlugin extends AbstractPlugin implements StoreViewExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const TEMPLATE_PATH = '@CurrencyGui/_partials/_blocks/currency-store-relation.twig';

    /**
     * {@inheritDoc}
     * - Returns template path for assigned countries.
     *
     * @api
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }

    /**
     * {@inheritDoc}
     * - Returns table with assigned currencies.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<mixed>
     */
    public function getTemplateData(StoreTransfer $storeTransfer): array
    {
        $currenciesTable = $this->getFactory()
            ->createAssignedCurrencyStoreTable($storeTransfer->getIdStoreOrFail())
            ->render();

        return [
            'currenciesTable' => $currenciesTable,
        ];
    }
}
