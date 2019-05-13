<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class ListContentByTypeController extends AbstractController
{
    public const PARAM_CONTENT_TYPE = 'type';
    public const PARAM_CONTENT_ID = 'idContent';
    public const PARAM_CONTENT_TEMPLATE = 'template';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $contentType = $request->query->get(static::PARAM_CONTENT_TYPE);
        $idContent = $request->query->get(static::PARAM_CONTENT_ID);
        $selectedTemplateIdentifier = $request->query->get(static::PARAM_CONTENT_TEMPLATE);
        $contentByTypeTable = $this->getFactory()->createContentByTypeTable($contentType, $idContent);
        $contentTypeTemplates = $this->getFactory()->createContentEditorPluginsResolver()->getTemplatesByType($contentType);
        $twigFunctionTemplate = $this->getFactory()->createContentEditorPluginsResolver()->getTwigFunctionTemplateByType($contentType);

        $data = [
            'table' => $contentByTypeTable->render(),
            'templates' => $contentTypeTemplates,
            'twigFunctionTemplate' => $twigFunctionTemplate,
            'selectedTemplateIdentifier' => $selectedTemplateIdentifier,
        ];

        return $this->viewResponse($data);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $contentType = $request->query->get(static::PARAM_CONTENT_TYPE);
        $idContent = $request->query->get(static::PARAM_CONTENT_ID);
        $contentByTypeTable = $this->getFactory()->createContentByTypeTable($contentType, $idContent);

        return $this->jsonResponse($contentByTypeTable->fetchData());
    }
}
