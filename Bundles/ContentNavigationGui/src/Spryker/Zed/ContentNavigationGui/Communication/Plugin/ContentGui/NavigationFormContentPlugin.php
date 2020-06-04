<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Plugin\ContentGui;

use Spryker\Shared\ContentNavigationGui\ContentNavigationGuiConfig;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface;
use Spryker\Zed\ContentNavigationGui\Communication\Form\NavigationContentTermForm;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentNavigationGui\Communication\ContentNavigationGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentNavigationGui\ContentNavigationGuiConfig getConfig()
 */
class NavigationFormContentPlugin extends AbstractPlugin implements ContentPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTermKey(): string
    {
        return ContentNavigationGuiConfig::CONTENT_TERM_NAVIGATION;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTypeKey(): string
    {
        return ContentNavigationGuiConfig::CONTENT_TYPE_NAVIGATION;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getForm(): string
    {
        return NavigationContentTermForm::class;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTermTransfer
     */
    public function getTransferObject(?array $params = null): TransferInterface
    {
        return $this->getFactory()
            ->createContentNavigationFormDataMapper()
            ->mapFormDataToContentNavigationTermTransfer($params);
    }
}
