<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection;

use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\Type\DoubleSubmitFormType;
use Symfony\Component\Form\AbstractExtension;

/**
 * @deprecated Use `Spryker\Shared\Form\DoubleSubmitProtection\DoubleSubmitProtectionExtension` instead.
 */
class DoubleSubmitProtectionExtension extends AbstractExtension
{
    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $tokenProvider
     */
    protected $tokenGenerator;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $tokenProvider
     */
    protected $tokenStorage;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface|null
     */
    protected $translator;

    /**
     * @var string|null
     */
    protected $translationDomain;

    /**
     * @param \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $tokenGenerator
     * @param \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $tokenStorage
     * @param \Symfony\Component\Translation\TranslatorInterface|null $translator
     * @param string|null $translationDomain
     */
    public function __construct(
        TokenGeneratorInterface $tokenGenerator,
        StorageInterface $tokenStorage,
        $translator = null,
        $translationDomain = null
    ) {
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * @return array
     */
    protected function loadTypeExtensions()
    {
        return [
            new DoubleSubmitFormType(
                $this->tokenGenerator,
                $this->tokenStorage,
                $this->translator,
                $this->translationDomain
            ),
        ];
    }
}
