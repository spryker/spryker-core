<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form\Type\Extension;

use Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BasicMultiFactorAuthTypeExtension extends AbstractTypeExtension
{
    /**
     * @var string
     */
    protected const ROUTE_PARAM = '_route';

    /**
     * @var bool
     */
    protected bool $isValidationRequired = true;

    /**
     * @param \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig $config
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(
        protected MultiFactorAuthConfig $config,
        protected RequestStack $requestStack
    ) {
    }

    /**
     * @return array<string>
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function assertFormMustNotBeValidated(FormInterface $form): bool
    {
        $formName = $form->getName();
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            return true;
        }

        $route = $request->attributes->get(static::ROUTE_PARAM);
        $enabledRoutesAndForms = $this->config->getEnabledRoutesAndForms();

        return isset($enabledRoutesAndForms[$route]) === false || in_array($formName, $enabledRoutesAndForms[$route], true) === false;
    }
}
