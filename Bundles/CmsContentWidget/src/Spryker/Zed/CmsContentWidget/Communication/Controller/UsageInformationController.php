<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsContentWidget\Communication\CmsContentWidgetCommunicationFactory getFactory()
 */
class UsageInformationController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $cmsContentWidgetTemplateList = $this->getFacade()
            ->getContentWidgetConfigurationList();

        return [
            'cmsContentWidgetTemplateList' => $cmsContentWidgetTemplateList,
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function jsonAction()
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
