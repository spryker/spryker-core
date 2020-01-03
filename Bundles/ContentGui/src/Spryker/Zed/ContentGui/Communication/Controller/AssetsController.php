<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class AssetsController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $editorContentTypes = $this->getFactory()->createContentEditorPluginsResolver()->getContentTypes();
        $editorContentWidgetTemplate = $this->getFactory()->getConfig()->getEditorContentWidgetTemplate();
        $maxWidgetNumber = $this->getFactory()->getConfig()->getMaxWidgetNumber();

        return $this->viewResponse([
            'editorContentTypes' => $editorContentTypes,
            'editorContentWidgetTemplate' => $editorContentWidgetTemplate,
            'maxWidgetNumber' => $maxWidgetNumber,
        ]);
    }
}
