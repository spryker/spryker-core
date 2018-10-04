<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Helper;

use Codeception\Module;
use Codeception\Stub;
use Generated\Shared\Transfer\DependencyValidationRequestTransfer;
use Generated\Shared\Transfer\DependencyValidationResponseTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface;
use Spryker\Zed\Development\Business\DependencyTree\ComposerDependencyParserInterface;
use Spryker\Zed\Development\Business\DevelopmentBusinessFactory;
use Spryker\Zed\Development\Business\DevelopmentFacade;
use Spryker\Zed\Development\Business\DevelopmentFacadeInterface;

class DependencyValidatorModule extends Module
{
    /**
     * @param array $composerDependency
     *
     * @return \Spryker\Zed\Development\Business\DevelopmentFacadeInterface
     */
    public function getFacadeForDependencyTests(array $composerDependency): DevelopmentFacadeInterface
    {
        $developmentFacade = new DevelopmentFacade();
        $developmentFactory = Stub::make(DevelopmentBusinessFactory::class, [
            'createModuleDependencyParser' => function () {
                return Stub::makeEmpty(ModuleDependencyParserInterface::class);
            },
            'createComposerDependencyParser' => function () use ($composerDependency) {
                return Stub::makeEmpty(ComposerDependencyParserInterface::class, [
                    'getComposerDependencyComparison' => function () use ($composerDependency) {
                        return [$composerDependency];
                    },
                ]);
            },
        ]);

        $developmentFacade->setFactory($developmentFactory);

        return $developmentFacade;
    }

    /**
     * @param string $moduleName
     * @param string|null $dependencyType
     *
     * @return \Generated\Shared\Transfer\DependencyValidationRequestTransfer
     */
    public function getDependencyValidationRequestTransfer(string $moduleName = 'Default', ?string $dependencyType = null): DependencyValidationRequestTransfer
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName('Spryker');

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($moduleName)
            ->setOrganization($organizationTransfer);

        $dependencyValidationRequestTransfer = new DependencyValidationRequestTransfer();
        $dependencyValidationRequestTransfer->setModule($moduleTransfer)->setDependencyType($dependencyType);

        return $dependencyValidationRequestTransfer;
    }

    /**
     * Valid dependency as it is not in src, not in tests and isOptional
     *
     * @return array
     */
    public function getDevOnlyComposerDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => [],
            'isOptional' => true,
            'src' => '',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => 'Foo',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency as found in src but not in require
     *
     * @return array
     */
    public function getInvalidSourceDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => 'Foo',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => '',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency as not found in src but in require
     *
     * @return array
     */
    public function getInvalidRequireDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => '',
            'tests' => '',
            'composerRequire' => 'Foo',
            'composerRequireDev' => '',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Valid dependency as found in src and in require
     *
     * @return array
     */
    public function getValidSourceDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => 'Foo',
            'tests' => '',
            'composerRequire' => 'Foo',
            'composerRequireDev' => '',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency as found in test but not in require-dev
     *
     * @return array
     */
    public function getInvalidTestDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => '',
            'tests' => 'Foo',
            'composerRequire' => '',
            'composerRequireDev' => '',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency as not found in test but in require-dev
     *
     * @return array
     */
    public function getInvalidRequireDevDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => '',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => 'Foo',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Valid dependency as found in test and in require-dev
     *
     * @return array
     */
    public function getValidTestDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => '',
            'tests' => 'Foo',
            'composerRequire' => '',
            'composerRequireDev' => 'Foo',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency marked as optional found in require
     *
     * @return array
     */
    public function getInvalidOptionalRequiredDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => true,
            'src' => '',
            'tests' => '',
            'composerRequire' => 'Foo',
            'composerRequireDev' => '',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Valid dependency marked as optional and in require-dev
     *
     * @return array
     */
    public function getValidOptionalRequiredDevDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => true,
            'src' => '',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => 'Foo',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency marked as optional not found in require-de
     *
     * @return array
     */
    public function getInvalidOptionalNotRequiredDevDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => true,
            'src' => '',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => '',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency marked as optional not found in suggests
     *
     * @return array
     */
    public function getInvalidOptionalNotSuggestedDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => true,
            'src' => 'Foo',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => 'Foo',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency marked as optional not found in suggests
     *
     * @return array
     */
    public function getInvalidRequireAndRequireDevDependency(): array
    {
        return [
            'dependencyModule' => 'Foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => 'Foo',
            'tests' => 'Foo',
            'composerRequire' => 'Foo',
            'composerRequireDev' => 'Foo',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyValidationResponseTransfer $dependencyValidationResponseTransfer
     *
     * @return void
     */
    public function assertValidDependencies(DependencyValidationResponseTransfer $dependencyValidationResponseTransfer): void
    {
        foreach ($dependencyValidationResponseTransfer->getModuleDependencies() as $moduleDependency) {
            $this->assertTrue($moduleDependency->getIsValid(), sprintf('Expected valid dependency but "%s" is marked as invalid', $moduleDependency->getModule()));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyValidationResponseTransfer $dependencyValidationResponseTransfer
     *
     * @return void
     */
    public function assertInvalidDependencies(DependencyValidationResponseTransfer $dependencyValidationResponseTransfer): void
    {
        foreach ($dependencyValidationResponseTransfer->getModuleDependencies() as $moduleDependency) {
            $this->assertFalse($moduleDependency->getIsValid(), sprintf('Expected invalid dependency but "%s" is marked as valid', $moduleDependency->getModule()));
        }
    }
}
