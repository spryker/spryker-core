<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Controller;

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
}
