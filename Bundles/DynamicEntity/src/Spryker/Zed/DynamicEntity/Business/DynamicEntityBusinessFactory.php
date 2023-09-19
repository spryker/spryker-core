<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business;

use Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfigurationCreator;
use Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfigurationCreatorInterface;
use Spryker\Zed\DynamicEntity\Business\Installer\DynamicEntityInstaller;
use Spryker\Zed\DynamicEntity\Business\Installer\DynamicEntityInstallerInterface;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapper;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReader;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Updater\DynamicEntityConfigurationUpdater;
use Spryker\Zed\DynamicEntity\Business\Updater\DynamicEntityConfigurationUpdaterInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\RequestFieldValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\RequiredFieldValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\BooleanFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\DecimalFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\IntegerFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\StringFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration\AllowedTablesValidatorRule;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration\ResourceNameValidatorRule;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration\UniqueTableNameAliasValidatorRule;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\FieldTypeBooleanValidatorRule;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\FieldTypeDecimalValidatorRule;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\FieldTypeIntegerValidatorRule;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\FieldTypeStringValidatorRule;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Definition\RequiredFieldsValidatorRule;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface;
use Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriter;
use Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface;
use Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DynamicEntity\DynamicEntityConfig getConfig()
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface getRepository()
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface getEntityManager()
 */
class DynamicEntityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface
     */
    public function createDynamicEntityReader(): DynamicEntityReaderInterface
    {
        return new DynamicEntityReader(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface
     */
    public function createDynamicEntityWriter(): DynamicEntityWriterInterface
    {
        return new DynamicEntityWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createDynamicEntityValidator(),
            $this->createDynamicEntityUpdateValidator(),
            $this->getDynamicEntityPostCreatePlugins(),
            $this->getDynamicEntityPostUpdatePlugins(),
            $this->getConnection(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createDynamicEntityValidator(): DynamicEntityValidatorInterface
    {
        return new DynamicEntityValidator(
            $this->getDynamicEntityValidators(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createDynamicEntityUpdateValidator(): DynamicEntityValidatorInterface
    {
        return new DynamicEntityValidator(
            $this->getDynamicEntityUpdateValidators(),
        );
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface>
     */
    public function getDynamicEntityValidators(): array
    {
        return [
            $this->createRequestFieldValidator(),
            $this->createRequiredFieldValidator(),
            $this->createIntegerFieldTypeValidator(),
            $this->createStringFieldTypeValidator(),
            $this->createBooleanFieldTypeValidator(),
            $this->createDecimalFeildTypeValidator(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface>
     */
    public function getDynamicEntityUpdateValidators(): array
    {
        return [
            $this->createRequestFieldValidator(),
            $this->createIntegerFieldTypeValidator(),
            $this->createStringFieldTypeValidator(),
            $this->createBooleanFieldTypeValidator(),
            $this->createDecimalFeildTypeValidator(),
        ];
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createRequiredFieldValidator(): DynamicEntityValidatorInterface
    {
        return new RequiredFieldValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createRequestFieldValidator(): DynamicEntityValidatorInterface
    {
        return new RequestFieldValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createIntegerFieldTypeValidator(): DynamicEntityValidatorInterface
    {
        return new IntegerFieldTypeValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createStringFieldTypeValidator(): DynamicEntityValidatorInterface
    {
        return new StringFieldTypeValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createBooleanFieldTypeValidator(): DynamicEntityValidatorInterface
    {
        return new BooleanFieldTypeValidator();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createDecimalFeildTypeValidator(): DynamicEntityValidatorInterface
    {
        return new DecimalFieldTypeValidator();
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface>
     */
    public function getDynamicEntityPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(DynamicEntityDependencyProvider::PLUGINS_DYNAMIC_ENTITY_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface>
     */
    public function getDynamicEntityPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(DynamicEntityDependencyProvider::PLUGINS_DYNAMIC_ENTITY_POST_UPDATE);
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Installer\DynamicEntityInstallerInterface
     */
    public function createDynamicEntityInstaller(): DynamicEntityInstallerInterface
    {
        return new DynamicEntityInstaller(
            $this->getConfig(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createDynamicEntityMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfigurationCreatorInterface
     */
    public function createDynamicEntityConfigurationCreator(): DynamicEntityConfigurationCreatorInterface
    {
        return new DynamicEntityConfigurationCreator(
            $this->createDynamicEntityConfigurationValidator(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Updater\DynamicEntityConfigurationUpdaterInterface
     */
    public function createDynamicEntityConfigurationUpdater(): DynamicEntityConfigurationUpdaterInterface
    {
        return new DynamicEntityConfigurationUpdater(
            $this->createDynamicEntityConfigurationValidator(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface
     */
    public function createDynamicEntityConfigurationValidator(): DynamicEntityConfigurationValidatorInterface
    {
        return new DynamicEntityConfigurationValidator(
            $this->getConfigurationValidatorRules(),
            $this->getDefinitionValidatorRules(),
        );
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface>
     */
    public function getConfigurationValidatorRules(): array
    {
        return [
            $this->createAllowedTablesValidatorRule(),
            $this->createUniqueTableNameAliasValidatorRule(),
            $this->createResourceNameValidatorRule(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface>
     */
    public function getDefinitionValidatorRules(): array
    {
        return [
            $this->createFieldTypeBooleanValidatorRule(),
            $this->createFieldTypeDecimalValidatorRule(),
            $this->createFieldTypeIntegerValidatorRule(),
            $this->createFieldTypeStringValidatorRule(),
            $this->createRequiredFieldsValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface
     */
    public function createAllowedTablesValidatorRule(): ValidatorRuleInterface
    {
        return new AllowedTablesValidatorRule(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface
     */
    public function createRequiredFieldsValidatorRule(): ValidatorRuleInterface
    {
        return new RequiredFieldsValidatorRule();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface
     */
    public function createUniqueTableNameAliasValidatorRule(): ValidatorRuleInterface
    {
        return new UniqueTableNameAliasValidatorRule(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapper
     */
    public function createDynamicEntityMapper(): DynamicEntityMapper
    {
        return new DynamicEntityMapper();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface
     */
    public function createFieldTypeBooleanValidatorRule(): ValidatorRuleInterface
    {
        return new FieldTypeBooleanValidatorRule();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface
     */
    public function createFieldTypeDecimalValidatorRule(): ValidatorRuleInterface
    {
        return new FieldTypeDecimalValidatorRule();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface
     */
    public function createFieldTypeIntegerValidatorRule(): ValidatorRuleInterface
    {
        return new FieldTypeIntegerValidatorRule();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface
     */
    public function createFieldTypeStringValidatorRule(): ValidatorRuleInterface
    {
        return new FieldTypeStringValidatorRule();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface
     */
    public function createResourceNameValidatorRule(): ValidatorRuleInterface
    {
        return new ResourceNameValidatorRule();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionInterface
     */
    public function getConnection(): DynamicEntityToConnectionInterface
    {
        return $this->getProvidedDependency(DynamicEntityDependencyProvider::CONNECTION);
    }
}
