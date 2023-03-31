<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreViewExpanderPluginInterface;

/**
 * @method \Spryker\Zed\LocaleGui\Communication\LocaleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\LocaleGui\LocaleGuiConfig getConfig()
 */
class AssignedLocalesStoreViewExpanderPlugin extends AbstractPlugin implements StoreViewExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const TEMPLATE_PATH = '@LocaleGui/_partials/_blocks/locale-store-relation.twig';

    /**
     * {@inheritDoc}
     * - Returns template path for assigned locales.
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
     * - Returns table with assigned locales.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<mixed>
     */
    public function getTemplateData(StoreTransfer $storeTransfer): array
    {
        $localesTable = $this->getFactory()
            ->createAssignedLocaleStoreTable($storeTransfer->getIdStoreOrFail())
            ->render();

        return [
            'localesTable' => $localesTable,
        ];
    }
}
