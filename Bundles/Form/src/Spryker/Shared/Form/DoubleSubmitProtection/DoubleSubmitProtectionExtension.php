<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Form\DoubleSubmitProtection;

use Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface;
use Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface;
use Spryker\Shared\Form\DoubleSubmitProtection\Type\DoubleSubmitFormType;
use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Translation\TranslatorInterface;

class DoubleSubmitProtectionExtension extends AbstractExtension
{
    /**
     * @var \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $tokenProvider
     */
    protected $tokenGenerator;

    /**
     * @var \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $tokenProvider
     */
    protected $tokenStorage;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface|null
     */
    protected $translator;

    /**
     * @var null|string
     */
    protected $translationDomain;

    /**
     * @param \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $tokenGenerator
     * @param \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $tokenStorage
     * @param \Symfony\Component\Translation\TranslatorInterface|null $translator
     * @param string|null $translationDomain
     */
    public function __construct(
        TokenGeneratorInterface $tokenGenerator,
        StorageInterface $tokenStorage,
        ?TranslatorInterface $translator = null,
        ?string $translationDomain = null
    ) {
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * @return \Symfony\Component\Form\FormTypeExtensionInterface[]
     */
    protected function loadTypeExtensions(): array
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
