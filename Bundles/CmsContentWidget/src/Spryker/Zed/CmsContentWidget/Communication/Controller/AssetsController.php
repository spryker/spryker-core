<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsContentWidget\Communication\CmsContentWidgetCommunicationFactory getFactory()
 */
class AssetsController extends AbstractController
{
    /**
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return array
     */
    public function indexAction(): array
    {
        if (!$this->getFactory()->getConfig()->isEditorButtonEnabled()) {
            throw new BadRequestHttpException();
        }

        $cmsContentWidgetConfiguration = $this->getFacade()
            ->getContentWidgetConfigurationList();

        $cmsContentWidgetFunctions = [];
        foreach ($cmsContentWidgetConfiguration->getCmsContentWidgetConfigurationList() as $cmsContentWidgetConfigurationTransfer) {
            $cmsContentWidgetFunctions[] = $cmsContentWidgetConfigurationTransfer->getFunctionName();
        }

        return $this->viewResponse([
            'cmsContentWidgetFunctions' => $cmsContentWidgetFunctions,
        ]);
    }
}
