<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class ConfigurationController extends AbstractController
{
    /**
     * @return array
     */
    public function assetsAction(): array
    {
        $editorContentTypes = $this->getFactory()->createContentEditorPluginsResolver()->getContentTypes();
        $editorContentTypes[] = 'Abstract Product List';

        return $this->viewResponse([
            'editorContentTypes' => $editorContentTypes,
        ]);
    }
}
