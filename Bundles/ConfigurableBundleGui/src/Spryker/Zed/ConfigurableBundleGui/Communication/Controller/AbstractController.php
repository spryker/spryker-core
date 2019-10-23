<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Communication\ConfigurableBundleGuiCommunicationFactory getFactory()
 */
class AbstractController extends SprykerAbstractController
{
    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::indexAction()
     */
    protected const ROUTE_TEMPLATES_LIST = '/configurable-bundle-gui/template';

    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    protected const ERROR_MESSAGE_PARAM_ID = '%id%';

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messages
     *
     * @return void
     */
    protected function handleErrors(ArrayObject $messages): void
    {
        foreach ($messages as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue(), $messageTransfer->getParameters());
        }
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    protected function findConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->setTranslationLocales(new ArrayObject([$this->getFactory()->getLocaleFacade()->getCurrentLocale()]));

        return $this->getFactory()
            ->getConfigurableBundleFacade()
            ->findConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);
    }
}
