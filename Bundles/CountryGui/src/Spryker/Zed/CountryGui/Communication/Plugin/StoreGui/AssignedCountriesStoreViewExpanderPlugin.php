<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreViewExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CountryGui\Communication\CountryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CountryGui\CountryGuiConfig getConfig()
 */
class AssignedCountriesStoreViewExpanderPlugin extends AbstractPlugin implements StoreViewExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const TEMPLATE_PATH = '@CountryGui/_partials/_blocks/country-store-relation.twig';

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
     * - Returns table with assigned countries.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<mixed>
     */
    public function getTemplateData(StoreTransfer $storeTransfer): array
    {
        $countriesTable = $this->getFactory()
            ->createAssignedCountryStoreTable($storeTransfer->getIdStoreOrFail())
            ->render();

        return [
            'countriesTable' => $countriesTable,
        ];
    }
}
