<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Strategy;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class AttachmentScopeStrategyResolver implements AttachmentScopeStrategyResolverInterface
{
    /**
     * @var array<string, \SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\AttachmentScopeStrategyInterface>
     */
    protected array $strategies = [];

    /**
     * @param array<\SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\AttachmentScopeStrategyInterface> $strategies
     */
    public function __construct(array $strategies)
    {
        foreach ($strategies as $strategy) {
            $this->strategies[$strategy->getScopeType()] = $strategy;
        }
    }

    public function canProcessScope(string $scopeType, FormInterface $form): bool
    {
        $strategy = $this->getStrategy($scopeType);
        if ($strategy === null) {
            return false;
        }

        return $strategy->canProcess($form);
    }

    /**
     * @param string $scopeType
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>|null
     */
    public function getFormDataForScope(string $scopeType, Request $request): ?array
    {
        $strategy = $this->getStrategy($scopeType);
        if ($strategy === null) {
            return null;
        }

        return $strategy->getFormData($request);
    }

    protected function getStrategy(string $scopeType): ?AttachmentScopeStrategyInterface
    {
        return $this->strategies[$scopeType] ?? null;
    }

    /**
     * @return array<string>
     */
    public function getSupportedScopeTypes(): array
    {
        return array_keys($this->strategies);
    }
}
