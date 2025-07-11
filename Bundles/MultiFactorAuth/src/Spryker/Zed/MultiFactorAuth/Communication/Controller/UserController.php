<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 */
class UserController extends AbstractUserController
{
    /**
     * @uses {@link \Spryker\Zed\SecurityGui\Communication\Plugin\MultiFactorAuth\PostUserLoginMultiFactorAuthenticationPlugin::USER_POST_AUTHENTICATION_TYPE}
     *
     * @var string
     */
    protected const USER_POST_AUTHENTICATION_TYPE = 'USER_POST_AUTHENTICATION_TYPE';

    /**
     * @var string
     */
    protected const DATA_ERROR_PARAMETER = 'data-error';

    /**
     * @var string
     */
    protected const DATA_SUCCESS_PARAMETER = 'data-success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $responseData
     *
     * @return array<mixed>
     */
    protected function returnGetEnabledTypesResponse(Request $request, array $responseData): array
    {
        return $this->viewResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $responseData
     * @param string $responseType
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function returnSendCodeResponse(Request $request, array $responseData, string $responseType): Response
    {
        return $this->renderView(
            $this->getSendCodeTemplatePath(),
            $responseData,
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $result
     * @param string|null $formName
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function returnValidationResponse(Request $request, ?string $result = null, ?string $formName = null): Response
    {
        return $this->renderView(
            $this->getValidationResponseTemplatePath(),
            ['dataResult' => $result === static::VALIDATION_RESPONSE_ERROR ? static::DATA_ERROR_PARAMETER : static::DATA_SUCCESS_PARAMETER],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function executePostLoginMultiFactorAuthenticationPlugins(UserTransfer $userTransfer): void
    {
        foreach ($this->getFactory()->getPostLoginMultiFactorAuthenticationPlugins() as $plugin) {
            if ($plugin->isApplicable(static::USER_POST_AUTHENTICATION_TYPE) === false) {
                continue;
            }

            $plugin->createToken($userTransfer->getUsernameOrFail());
            $plugin->executeOnAuthenticationSuccess($userTransfer);
        }
    }

    /**
     * @return string
     */
    protected function getSendCodeTemplatePath(): string
    {
        return '@MultiFactorAuth/User/send-code.twig';
    }

    /**
     * @return string
     */
    protected function getGetEnabledTypesTemplatePath(): string
    {
        return '@MultiFactorAuth/User/get-enabled-types.twig';
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getTypeSelectionForm(array $options = []): FormInterface
    {
        return $this->getFactory()->getTypeSelectionForm($options);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getCodeValidationForm(array $options = []): FormInterface
    {
        return $this->getFactory()->getCodeValidationForm($options);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider
     */
    protected function getTypeSelectionFormDataProvider(): TypeSelectionFormDataProvider
    {
        return $this->getFactory()->createTypeSelectionFormDataProvider();
    }
}
