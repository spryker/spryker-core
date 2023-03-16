<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Processor\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\OauthBackendApi\OauthBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class UserRequestValidator implements UserRequestValidatorInterface
{
    /**
     * @var list<\Spryker\Glue\OauthBackendApiExtension\Dependency\Plugin\UserRequestValidationPreCheckerPluginInterface>
     */
    protected array $userRequestValidationPreCheckerPlugins = [];

    /**
     * @param list<\Spryker\Glue\OauthBackendApiExtension\Dependency\Plugin\UserRequestValidationPreCheckerPluginInterface> $userRequestValidationPreCheckerPlugins
     */
    public function __construct(
        array $userRequestValidationPreCheckerPlugins
    ) {
        $this->userRequestValidationPreCheckerPlugins = $userRequestValidationPreCheckerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $glueRequestValidationTransfer = (new GlueRequestValidationTransfer())
            ->setIsValid(false);
        $glueRequestValidationTransfer = $this->executeUserRequestValidationPreCheckerPlugins(
            $glueRequestValidationTransfer,
            $glueRequestTransfer,
        );

        if ($glueRequestValidationTransfer->getIsValid()) {
            return $glueRequestValidationTransfer;
        }

        if ($this->headerAuthorizationExist($glueRequestTransfer) && $glueRequestTransfer->getRequestUser() === null) {
            return $glueRequestValidationTransfer
                ->setIsValid(false)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->addError($this->createGlueError());
        }

        return $glueRequestValidationTransfer->setIsValid(true);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function executeUserRequestValidationPreCheckerPlugins(
        GlueRequestValidationTransfer $glueRequestValidationTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueRequestValidationTransfer {
        foreach ($this->userRequestValidationPreCheckerPlugins as $userRequestValidationPreCheckerPlugin) {
            $glueRequestValidationTransfer = $userRequestValidationPreCheckerPlugin->preCheck(
                $glueRequestTransfer,
                $glueRequestValidationTransfer,
            );
        }

        return $glueRequestValidationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function headerAuthorizationExist(GlueRequestTransfer $glueRequestTransfer): bool
    {
        return $glueRequestTransfer->getMeta() &&
            array_key_exists(OauthBackendApiConfig::HEADER_AUTHORIZATION, $glueRequestTransfer->getMeta());
    }

    /**
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createGlueError(): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(OauthBackendApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID)
            ->setMessage(OauthBackendApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN);
    }
}
