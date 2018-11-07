<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OpenApiSpecificationPathGenerator implements OpenApiSpecificationPathGeneratorInterface
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
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRendererInterface
     */
    protected $pathMethodRenderer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRendererInterface $pathMethodRenderer
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
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addGetPath(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer,
        ?OpenApiSpecificationPathSchemaDataTransfer $responseSchemaDataTransfer
    ): void {
        if (!$responseSchemaDataTransfer) {
            $responseSchemaDataTransfer = new OpenApiSpecificationPathSchemaDataTransfer();
        }
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
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addPostPath(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $requestSchemaDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer,
        ?OpenApiSpecificationPathSchemaDataTransfer $responseSchemaDataTransfer
    ): void {
        if (!$responseSchemaDataTransfer) {
            $responseSchemaDataTransfer = new OpenApiSpecificationPathSchemaDataTransfer();
        }
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
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addPatchPath(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $requestSchemaDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer,
        ?OpenApiSpecificationPathSchemaDataTransfer $responseSchemaDataTransfer
    ): void {
        if (!$responseSchemaDataTransfer) {
            $responseSchemaDataTransfer = new OpenApiSpecificationPathSchemaDataTransfer();
        }
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
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addDeletePath(
        OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer,
        OpenApiSpecificationPathSchemaDataTransfer $errorSchemaDataTransfer
    ): void {
        $responseSchemaDataTransfer = new OpenApiSpecificationPathSchemaDataTransfer();
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
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return void
     */
    protected function addPath(OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer): void
    {
        $this->paths = array_replace_recursive($this->paths, $this->pathMethodRenderer->render($pathMethodDataTransfer));
    }
}
