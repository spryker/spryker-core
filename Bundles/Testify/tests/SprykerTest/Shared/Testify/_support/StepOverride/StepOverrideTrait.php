<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\StepOverride;

trait StepOverrideTrait
{
    /**
     * @var \SprykerTest\Shared\Testify\StepOverride\StepOverrider|null
     */
    protected $stepOverrider;

    /**
     * @param string $stepDescription
     *
     * @return static
     */
    public function amSure(string $stepDescription): self
    {
        return $this->overrideStep('am sure ' . $stepDescription);
    }

    /**
     * @param string $stepDescription
     *
     * @return static
     */
    public function assume(string $stepDescription): self
    {
        return $this->overrideStep('assume ' . $stepDescription);
    }

    /**
     * @return static
     */
    public function whenI(): self
    {
        if ($this->stepOverrider !== null) {
            $this->stepOverrider->addPreposition(' when I ');
        }

        return $this;
    }

    /**
     * @return static
     */
    public function ifI(): self
    {
        if ($this->stepOverrider !== null) {
            $this->stepOverrider->addPreposition(' if I ');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getScenario()
    {
        return $this->stepOverrider ?? parent::getScenario();
    }

    /**
     * @param string $stepDescription
     *
     * @return static
     */
    public function overrideStep(string $stepDescription): self
    {
        $scenario = $this->getScenario();

        $this->stepOverrider = new StepOverrider($scenario, $stepDescription, [$this, 'releaseStep']);

        return $this;
    }

    /**
     * @return static
     */
    public function releaseStep(): self
    {
        $this->stepOverrider = null;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return static
     */
    public function amSureResponseDataContainsSingleResourceOfType(string $type): self
    {
        return $this->amSure(sprintf('The returned resource is of type %s', $type));
    }

    /**
     * @return static
     */
    public function amSureSingleResourceIdEqualTo(): self
    {
        return $this->amSure('The returned resource has correct id');
    }

    /**
     * @return static
     */
    public function amSureSingleResourceHasSelfLink(): self
    {
        return $this->amSure('The returned resource has correct self-link');
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return static
     */
    public function amSureSingleResourceHasRelationshipByTypeAndId(string $type, string $id): self
    {
        return $this->amSure(
            sprintf('The returned resource has a relation to resource of type %s with id %s', $type, $id)
        );
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return static
     */
    public function amSureIncludesContainsResourceByTypeAndId(string $type, string $id): self
    {
        return $this->amSure(
            sprintf('The returned resource has include of type %s with id %s', $type, $id)
        );
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return static
     */
    public function amSureIncludedResourceByTypeAndIdHasSelfLink(string $type, string $id): self
    {
        return $this->amSure(
            sprintf('The include of type %s with id %s has correct self-link', $type, $id)
        );
    }

    /**
     * @param string $type
     * @param string $id
     * @param string $relationType
     * @param string $relationId
     *
     * @return static
     */
    public function amSureIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
        string $type,
        string $id,
        string $relationType,
        string $relationId
    ): self {
        return $this->amSure(
            sprintf('The resource of type %s with id %s has a relation to the resource of type %s with id %s', $type, $id, $relationType, $relationId)
        );
    }

    /**
     * @param string $type
     *
     * @return static
     */
    public function amSureResponseDataContainsResourceCollectionOfType(string $type): self
    {
        return $this->amSure(sprintf('The response data contains resource collection of type %s', $type));
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public function amSureResourceCollectionHasResourceWithId(string $id): self
    {
        return $this->amSure(sprintf('The response resource collection has resource with id %s', $id));
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public function amSureResourceByIdHasSelfLink(string $id): self
    {
        return $this->amSure(sprintf('The resource with id %s has correct self-link', $id));
    }

    /**
     * @param string $id
     * @param string $relationType
     * @param string $relationId
     *
     * @return static
     */
    public function amSureResourceByIdHasRelationshipByTypeAndId(string $id, string $relationType, string $relationId): self
    {
        return $this->amSure(
            sprintf('The resource with id %s has a relation to resource of type %s with id %s', $id, $relationType, $relationId)
        );
    }

    /**
     * @param string $attribute
     *
     * @return static
     */
    public function amSureSingleResourceHasAttribute(string $attribute): self
    {
        return $this->amSure(sprintf('The returned resource contains `%s` attribute', $attribute));
    }
}
