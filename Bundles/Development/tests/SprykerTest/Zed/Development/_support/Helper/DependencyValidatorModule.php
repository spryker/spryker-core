<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Helper;

use Codeception\Module;
use Codeception\Stub;
use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\DependencyValidationRequestTransfer;
use Generated\Shared\Transfer\DependencyValidationResponseTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface;
use Spryker\Zed\Development\Business\DependencyTree\ComposerDependencyParserInterface;
use Spryker\Zed\Development\Business\DevelopmentBusinessFactory;
use Spryker\Zed\Development\Business\DevelopmentFacade;
use Spryker\Zed\Development\Business\DevelopmentFacadeInterface;
use Spryker\Zed\Development\DevelopmentDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DependencyValidatorModule extends Module
{
    /**
     * @param array $composerDependency
     *
     * @return \Spryker\Zed\Development\Business\DevelopmentFacadeInterface
     */
    public function getFacadeForDependencyTests(array $composerDependency): DevelopmentFacadeInterface
    {
        /** @var \Spryker\Zed\Development\Business\DevelopmentBusinessFactory $developmentFactory */
        $developmentFactory = $this->getDevelopmentFactory($composerDependency);
        $developmentFactory->setContainer($this->getContainerWithProvidedDependencies());

        $developmentFacade = new DevelopmentFacade();
        $developmentFacade->setFactory($developmentFactory);

        return $developmentFacade;
    }

    /**
     * @param array $composerDependency
     *
     * @return \Spryker\Zed\Development\Business\DevelopmentBusinessFactory|object
     */
    protected function getDevelopmentFactory(array $composerDependency)
    {
        $developmentFactory = Stub::make(DevelopmentBusinessFactory::class, [
            'createModuleDependencyParser' => function () {
                return Stub::makeEmpty(ModuleDependencyParserInterface::class, [
                    'parseOutgoingDependencies' => function () {
                        return new DependencyCollectionTransfer();
                    },
                ]);
            },
            'createComposerDependencyParser' => function () use ($composerDependency) {
                return Stub::makeEmpty(ComposerDependencyParserInterface::class, [
                    'getComposerDependencyComparison' => function () use ($composerDependency) {
                        return [$composerDependency];
                    },
                ]);
            },
        ]);

        return $developmentFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainerWithProvidedDependencies(): Container
    {
        $container = new Container();
        $developmentDependencyProvider = new DevelopmentDependencyProvider();
        $container = $developmentDependencyProvider->provideBusinessLayerDependencies($container);

        return $container;
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
            ->setName('Spryker')
            ->setNameDashed('spryker');

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($moduleName)
            ->setNameDashed(strtolower($moduleName))
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => [],
            'isOptional' => true,
            'src' => '',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => '',
            'tests' => '',
            'composerRequire' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => 'bar/foo',
            'tests' => '',
            'composerRequire' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => '',
            'tests' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => '',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => '',
            'tests' => 'bar/foo',
            'composerRequire' => '',
            'composerRequireDev' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => true,
            'src' => '',
            'tests' => '',
            'composerRequire' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => true,
            'src' => '',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => 'bar/foo',
            'suggested' => '',
            'isOwnExtensionModule' => false,
        ];
    }

    /**
     * Invalid dependency marked as optional not found in require-dev
     *
     * @return array
     */
    public function getInvalidOptionalNotRequiredDevDependency(): array
    {
        return [
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => true,
            'src' => 'bar/foo',
            'tests' => '',
            'composerRequire' => '',
            'composerRequireDev' => 'bar/foo',
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
            'moduleName' => 'Foo',
            'composerName' => 'bar/foo',
            'types' => ['foo'],
            'isOptional' => false,
            'src' => 'bar/foo',
            'tests' => 'bar/foo',
            'composerRequire' => 'bar/foo',
            'composerRequireDev' => 'bar/foo',
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
            $this->assertTrue($moduleDependency->getIsValid(), sprintf('Expected valid dependency but "%s" is marked as invalid', $moduleDependency->getModuleName()));
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
            $this->assertFalse($moduleDependency->getIsValid(), sprintf('Expected invalid dependency but "%s" is marked as valid', $moduleDependency->getModuleName()));
        }
    }
}
