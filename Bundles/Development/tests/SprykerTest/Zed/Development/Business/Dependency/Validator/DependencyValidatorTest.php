<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\Dependency\Validator;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group Dependency
 * @group Validator
 * @group DependencyValidatorTest
 * Add your own group annotations below this line
 */
class DependencyValidatorTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDependencyIsValidWhenDependencyTypeIsDevOnly(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getDevOnlyComposerDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertValidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsInvalidWhenDependencyInSourceButMissingInComposerRequire(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidSourceDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsInvalidWhenDependencyNotInSourceButInComposerRequire(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidRequireDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsValidWhenDependencyInSourceAndInComposerRequire(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getValidSourceDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertValidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsInvalidWhenDependencyInTestButMissingInComposerRequireDev(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidTestDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsInvalidWhenDependencyNotInTestButInComposerRequireDev(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidRequireDevDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsValidWhenDependencyInTestAndInComposerRequireDev(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidTestDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsValidWhenDependencyIsOptionalAndNotInRequire(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getValidOptionalRequiredDevDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertValidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsInvalidWhenDependencyIsOptionalButInRequire(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidOptionalRequiredDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsInvalidWhenDependencyIsOptionalButNotInRequireDev(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidOptionalNotRequiredDevDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsInvalidWhenDependencyIsOptionalButNotSuggested(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidOptionalNotSuggestedDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testDependencyIsInvalidWhenDependencyInRequireAndInRequireDev(): void
    {
        $developmentFacade = $this->tester->getFacadeForDependencyTests($this->tester->getInvalidRequireAndRequireDevDependency());
        $dependencyValidationResponseTransfer = $developmentFacade->validateModuleDependencies($this->tester->getDependencyValidationRequestTransfer());

        $this->tester->assertInvalidDependencies($dependencyValidationResponseTransfer);
    }
}
