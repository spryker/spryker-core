<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Validator;

use Generated\Shared\Transfer\DependencyValidationRequestTransfer;
use Generated\Shared\Transfer\DependencyValidationResponseTransfer;
use Generated\Shared\Transfer\ModuleDependencyTransfer;
use Spryker\Zed\Development\Business\Composer\ComposerNameFinderInterface;
use Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface;
use Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface;
use Spryker\Zed\Development\Business\DependencyTree\ComposerDependencyParserInterface;

class DependencyValidator implements DependencyValidatorInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface
     */
    protected $moduleDependencyParser;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\ComposerDependencyParserInterface
     */
    protected $composerDependencyParser;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface
     */
    protected $validationRule;

    /**
     * @var \Spryker\Zed\Development\Business\Composer\ComposerNameFinderInterface
     */
    protected $composerNameFinder;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface $moduleDependencyParser
     * @param \Spryker\Zed\Development\Business\DependencyTree\ComposerDependencyParserInterface $composerDependencyParser
     * @param \Spryker\Zed\Development\Business\Dependency\Validator\ValidationRules\ValidationRuleInterface $validationRule
     * @param \Spryker\Zed\Development\Business\Composer\ComposerNameFinderInterface $composerNameFinder
     */
    public function __construct(
        ModuleDependencyParserInterface $moduleDependencyParser,
        ComposerDependencyParserInterface $composerDependencyParser,
        ValidationRuleInterface $validationRule,
        ComposerNameFinderInterface $composerNameFinder
    ) {
        $this->moduleDependencyParser = $moduleDependencyParser;
        $this->composerDependencyParser = $composerDependencyParser;
        $this->validationRule = $validationRule;
        $this->composerNameFinder = $composerNameFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyValidationRequestTransfer $dependencyValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyValidationResponseTransfer
     */
    public function validate(DependencyValidationRequestTransfer $dependencyValidationRequestTransfer): DependencyValidationResponseTransfer
    {
        $moduleDependencyTransferCollection = $this->buildModuleDependencyTransferCollection($dependencyValidationRequestTransfer);
        $dependencyValidationResponseTransfer = new DependencyValidationResponseTransfer();

        foreach ($moduleDependencyTransferCollection as $moduleDependencyTransfer) {
            $dependencyValidationResponseTransfer->addModuleDependency($this->validationRule->validateModuleDependency($moduleDependencyTransfer));
        }

        return $dependencyValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyValidationRequestTransfer $dependencyValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer[]
     */
    protected function buildModuleDependencyTransferCollection(DependencyValidationRequestTransfer $dependencyValidationRequestTransfer): array
    {
        $composerDependencies = $this->getComposerDependencies($dependencyValidationRequestTransfer);

        return $this->formatDependencies($composerDependencies);
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyValidationRequestTransfer $dependencyValidationRequestTransfer
     *
     * @return array
     */
    protected function getComposerDependencies(DependencyValidationRequestTransfer $dependencyValidationRequestTransfer): array
    {
        $moduleDependencies = $this->moduleDependencyParser->parseOutgoingDependencies(
            $dependencyValidationRequestTransfer->getModule(),
            $dependencyValidationRequestTransfer->getDependencyType()
        );
        foreach ($moduleDependencies->getDependencyModules() as $dependencyModuleTransfer) {
            $composerName = $dependencyModuleTransfer->getComposerName() ?? $this->composerNameFinder->findComposerNameByModuleName($dependencyModuleTransfer->getModule());
            $dependencyModuleTransfer->setComposerName($composerName);
        }
        $composerDependencies = $this->composerDependencyParser->getComposerDependencyComparison($moduleDependencies);

        return $composerDependencies;
    }

    /**
     * @param array $composerDependencies
     *
     * @return \Generated\Shared\Transfer\ModuleDependencyTransfer[]
     */
    protected function formatDependencies(array $composerDependencies): array
    {
        $moduleDependencyTransferCollection = [];
        foreach ($composerDependencies as $composerDependency) {
            $moduleDependencyTransfer = new ModuleDependencyTransfer();
            $moduleDependencyTransfer
                ->setModuleName($composerDependency['moduleName'])
                ->setComposerName($composerDependency['composerName'])
                ->setIsValid(true)
                ->setDependencyTypes($this->getDependencyTypes($composerDependency))
                ->setIsOptionalDependency($composerDependency['isOptional'])
                ->setIsSrcDependency(($composerDependency['src'] === '') ? false : true)
                ->setIsTestDependency(($composerDependency['tests'] === '') ? false : true)
                ->setIsInComposerRequire(($composerDependency['composerRequire'] === '') ? false : true)
                ->setIsInComposerRequireDev(($composerDependency['composerRequireDev'] === '') ? false : true)
                ->setIsSuggested(($composerDependency['suggested'] === '') ? false : true)
                ->setIsOwnExtensionModule($composerDependency['isOwnExtensionModule']);

            $moduleDependencyTransferCollection[] = $moduleDependencyTransfer;
        }

        return $moduleDependencyTransferCollection;
    }

    /**
     * @param array $composerDependency
     *
     * @return array
     */
    protected function getDependencyTypes(array $composerDependency): array
    {
        $dependencyTypes = $composerDependency['types'];
        if (!$composerDependency['src'] && !$composerDependency['tests']) {
            $dependencyTypes[] = 'dev only';
        }

        return $dependencyTypes;
    }
}
