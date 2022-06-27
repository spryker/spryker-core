<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\RequestValidator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplication\Resource\GenericResource;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeDefinitionPluginInterface;
use Symfony\Component\HttpFoundation\Response;

class ScopeRequestAfterRoutingValidator implements RequestValidatorInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_FORBIDDEN = 'Forbidden.';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource
    ): GlueRequestValidationTransfer {
        if ($resource instanceof GenericResource && $glueRequestTransfer->getResourceOrFail()->getScope()) {
            if (
                $glueRequestTransfer->getRequestUser() &&
                !in_array($glueRequestTransfer->getResourceOrFail()->getScope(), $glueRequestTransfer->getRequestUser()->getScopes())
            ) {
                return $this->createNotValidGlueRequestValidationTransfer();
            }
        }

        if (!$resource instanceof ScopeDefinitionPluginInterface) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        if ($glueRequestTransfer->getRequestUser()) {
            $scopes = $resource->getScopes();
            $method = strtolower($glueRequestTransfer->getMethodOrFail());

            if (isset($scopes[$method]) && in_array($scopes[$method], $glueRequestTransfer->getRequestUser()->getScopes())) {
                return (new GlueRequestValidationTransfer())->setIsValid(true);
            }
        }

        return $this->createNotValidGlueRequestValidationTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function createNotValidGlueRequestValidationTransfer(): GlueRequestValidationTransfer
    {
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setMessage(static::MESSAGE_FORBIDDEN);

        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->addError($glueErrorTransfer)
            ->setStatus(Response::HTTP_FORBIDDEN);
    }
}
