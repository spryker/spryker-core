<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathMethodRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestApiDocumentationPathGenerator implements RestApiDocumentationPathGeneratorInterface
{
    protected const DESCRIPTION_DEFAULT_REQUEST = 'Expected request body.';
    protected const DESCRIPTION_DEFAULT_RESPONSE = 'Expected response to a bad request.';
    protected const DESCRIPTION_SUCCESSFUL_RESPONSE = 'Expected response to a valid request.';

    protected const KEY_DEFAULT = 'default';

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathMethodRendererInterface
     */
    protected $pathMethodRenderer;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\PathMethodRendererInterface $pathMethodRenderer
     */
    public function __construct(PathMethodRendererInterface $pathMethodRenderer)
    {
        $this->pathMethodRenderer = $pathMethodRenderer;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addGetPath(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void {
        $responseSchemaDataTransfer->setCode((string)Response::HTTP_OK);
        $responseSchemaDataTransfer->setDescription(static::DESCRIPTION_SUCCESSFUL_RESPONSE);

        $errorSchemaDataTransfer->setCode(static::KEY_DEFAULT);
        $errorSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_RESPONSE);

        $pathMethodDataTransfer->addResponseSchema($responseSchemaDataTransfer);
        $pathMethodDataTransfer->addResponseSchema($errorSchemaDataTransfer);
        $pathMethodDataTransfer->setMethod(strtolower(Request::METHOD_GET));

        $this->addPath($pathMethodDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addPostPath(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $requestSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void {
        $responseSchemaDataTransfer->setCode((string)Response::HTTP_CREATED);
        $responseSchemaDataTransfer->setDescription(static::DESCRIPTION_SUCCESSFUL_RESPONSE);

        $errorSchemaDataTransfer->setCode(static::KEY_DEFAULT);
        $errorSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_RESPONSE);

        $requestSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_REQUEST);

        $pathMethodDataTransfer->setRequestSchema($requestSchemaDataTransfer);
        $pathMethodDataTransfer->addResponseSchema($responseSchemaDataTransfer);
        $pathMethodDataTransfer->addResponseSchema($errorSchemaDataTransfer);
        $pathMethodDataTransfer->setMethod(strtolower(Request::METHOD_POST));

        $this->addPath($pathMethodDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addPatchPath(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $requestSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $responseSchemaDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void {
        $responseSchemaDataTransfer->setCode((string)Response::HTTP_ACCEPTED);
        $responseSchemaDataTransfer->setDescription(static::DESCRIPTION_SUCCESSFUL_RESPONSE);

        $errorSchemaDataTransfer->setCode(static::KEY_DEFAULT);
        $errorSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_RESPONSE);

        $requestSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_REQUEST);

        $pathMethodDataTransfer->setRequestSchema($requestSchemaDataTransfer);
        $pathMethodDataTransfer->addResponseSchema($responseSchemaDataTransfer);
        $pathMethodDataTransfer->addResponseSchema($errorSchemaDataTransfer);
        $pathMethodDataTransfer->setMethod(strtolower(Request::METHOD_PATCH));

        $this->addPath($pathMethodDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addDeletePath(
        RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer,
        RestApiDocumentationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void {
        $responseSchemaDataTransfer = new RestApiDocumentationPathSchemaDataTransfer();
        $responseSchemaDataTransfer->setCode((string)Response::HTTP_NO_CONTENT);
        $responseSchemaDataTransfer->setDescription(static::DESCRIPTION_SUCCESSFUL_RESPONSE);

        $errorSchemaDataTransfer->setCode(static::KEY_DEFAULT);
        $errorSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_RESPONSE);

        $pathMethodDataTransfer->addResponseSchema($responseSchemaDataTransfer);
        $pathMethodDataTransfer->addResponseSchema($errorSchemaDataTransfer);
        $pathMethodDataTransfer->setMethod(strtolower(Request::METHOD_DELETE));

        $this->addPath($pathMethodDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return void
     */
    protected function addPath(RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer): void
    {
        $this->paths += $this->pathMethodRenderer->render($pathMethodDataTransfer);
    }
}
