<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Business\CmsFacade getFacade()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainer getQueryContainer()
 */
class ContentWidgetConfigurationProviderController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction()
    {
        $cmsContentWidgetConfiguration = $this->getFacade()
            ->getContentWidgetConfigurationList()
            ->toArray();

        return new JsonResponse(
            $this->getFactory()
                ->getUtilEncodingService()
                ->encodeJson($cmsContentWidgetConfiguration)
        );
    }

}
