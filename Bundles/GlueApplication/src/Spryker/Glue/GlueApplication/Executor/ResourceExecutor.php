<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Executor;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\Cache\Reader\ControllerCacheReaderInterface;
use Spryker\Glue\GlueApplication\Exception\InvalidActionParametersException;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResourceExecutor implements ResourceExecutorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Cache\Reader\ControllerCacheReaderInterface;
     */
    protected $controllerCacheReader;

    /**
     * @param \Spryker\Glue\GlueApplication\Cache\Reader\ControllerCacheReaderInterface $controllerCacheReader
     */
    public function __construct(ControllerCacheReaderInterface $controllerCacheReader)
    {
        $this->controllerCacheReader = $controllerCacheReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function executeResource(
        ResourceInterface $resource,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $executableResource = $resource->getResource($glueRequestTransfer);

        $parameters = $this->controllerCacheReader->getActionParameters($executableResource, $resource, $glueRequestTransfer);
        if ($parameters === null) {
            return $this->createUnsupportedRequest(
                GlueApplicationConfig::ERROR_MESSAGE_METHOD_NOT_FOUND,
                GlueApplicationConfig::ERROR_CODE_METHOD_NOT_FOUND,
            );
        }

        if ($glueRequestTransfer->getContent()) {
            $attributesTransfer = $this->getAttributesTransfer($resource, $glueRequestTransfer);

            if (!$attributesTransfer) {
                return $this->callControllerAction($executableResource, $this->collectParameters($parameters, [$glueRequestTransfer]));
            }

            $attributesTransfer->fromArray($glueRequestTransfer->getAttributes(), true);
            $glueRequestTransfer->getResource()->setAttributes($attributesTransfer);

            return $this->callControllerAction($executableResource, $this->collectParameters($parameters, [$attributesTransfer, $glueRequestTransfer]));
        }

        if ($glueRequestTransfer->getResource()->getId()) {
            return $this->callControllerAction($executableResource, $this->collectParameters($parameters, [$glueRequestTransfer->getResource()->getId(), $glueRequestTransfer]));
        }

        return $this->callControllerAction($executableResource, $this->collectParameters($parameters, [$glueRequestTransfer]));
    }

    /**
     * @param callable():\Generated\Shared\Transfer\GlueResponseTransfer $executableResource
     * @param array<mixed> $parameters
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\InvalidActionParametersException
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function callControllerAction($executableResource, array $parameters): GlueResponseTransfer
    {
        try {
            $glueResponseTransfer = call_user_func_array($executableResource, $parameters);
        } catch (Throwable $exception) {
            throw new InvalidActionParametersException(
                'Method with requested parameters is not found.
                Run `glue glue-api:controller:cache:warm-up` to update a controller cache.',
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    protected function getAttributesTransfer(ResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): ?AbstractTransfer
    {
        $glueResourceMethodCollectionTransfer = $resource->getDeclaredMethods();

        $method = strtolower($glueRequestTransfer->getResource()->getMethod());
        if (!$glueResourceMethodCollectionTransfer->offsetExists($method)) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer|null $glueResourceMethodConfigurationTransfer */
        $glueResourceMethodConfigurationTransfer = $glueResourceMethodCollectionTransfer
            ->offsetGet($method);

        if ($glueResourceMethodConfigurationTransfer && $glueResourceMethodConfigurationTransfer->getAttributes()) {
            $attributeTransfer = $glueResourceMethodConfigurationTransfer->getAttributesOrFail();
            if (
                is_subclass_of($attributeTransfer, AbstractTransfer::class) &&
                !$attributeTransfer instanceof GlueRequestTransfer
            ) {
                return new $attributeTransfer();
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $parameters
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function collectParameters(array $parameters, array $options): array
    {
        $parameters = $this->setDefaultRequest($parameters);

        foreach ($options as $option) {
            if (is_object($option) && isset($parameters[get_class($option)])) {
                $parameters[get_class($option)] = $option;

                continue;
            }

            if (isset($parameters[getType($option)])) {
                $parameters[getType($option)] = $option;
            }
        }

        $parameters[GlueResponseTransfer::class] = new GlueResponseTransfer();

        return array_values($parameters);
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return array<string, mixed>
     */
    protected function setDefaultRequest(array $parameters): array
    {
        if (array_key_exists(Request::class, $parameters)) {
            $parameters[Request::class] = Request::createFromGlobals();
        }

        return $parameters;
    }

    /**
     * @param string $errorMessage
     * @param string $errorCode
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function createUnsupportedRequest(string $errorMessage, string $errorCode): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST)
            ->addError(
                (new GlueErrorTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setCode($errorCode)
                    ->setMessage($errorMessage),
            );
    }
}
