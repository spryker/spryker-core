<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business;

use Propel\Runtime\Map\DatabaseMap;
use Propel\Runtime\Propel;
use Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityCollectionRequestBuilder;
use Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityCollectionRequestBuilderInterface;
use Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityRelationConfigurationTreeBuilder;
use Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityRelationConfigurationTreeBuilderInterface;
use Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfigurationCreator;
use Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfigurationCreatorInterface;
use Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityCreator;
use Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityCreatorInterface;
use Spryker\Zed\DynamicEntity\Business\Expander\DynamicEntityPostEditRequestExpander;
use Spryker\Zed\DynamicEntity\Business\Expander\DynamicEntityPostEditRequestExpanderInterface;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexer;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface;
use Spryker\Zed\DynamicEntity\Business\Installer\DynamicEntityInstaller;
use Spryker\Zed\DynamicEntity\Business\Installer\DynamicEntityInstallerInterface;
use Spryker\Zed\DynamicEntity\Business\Installer\Validator\FieldMappingValidator;
use Spryker\Zed\DynamicEntity\Business\Installer\Validator\FieldMappingValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapper;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;
use Spryker\Zed\DynamicEntity\Business\Reader\DisallowedTablesReader;
use Spryker\Zed\DynamicEntity\Business\Reader\DisallowedTablesReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReader;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolver;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
use Spryker\Zed\DynamicEntity\Business\Updater\DynamicEntityConfigurationUpdater;
use Spryker\Zed\DynamicEntity\Business\Updater\DynamicEntityConfigurationUpdaterInterface;
use Spryker\Zed\DynamicEntity\Business\Updater\DynamicEntityUpdater;
use Spryker\Zed\DynamicEntity\Business\Updater\DynamicEntityUpdaterInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\Constraint\ConstraintInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\Constraint\UrlConstraint;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\ConstraintValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\RequestFieldValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\RequiredFieldValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\BooleanFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\DecimalFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\IntegerFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Field\Type\StringFieldTypeValidator;
use Spryker\Zed\DynamicEntity\Business\Validator\Relation\EditableRelationValidator;
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
            $this->createDynamicEntityMapper(),
            $this->createDynamicEntityConfigurationTreeValidator(),
            $this->createDynamicEntityRelationConfigurationTreeBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityCreatorInterface
     */
    public function createDynamicEntityCreator(): DynamicEntityCreatorInterface
    {
        return new DynamicEntityCreator(
            $this->createDynamicEntityReader(),
            $this->createDynamicEntityWriter(),
            $this->createDynamicEntityValidator(),
            $this->createDynamicEntityConfigurationTreeValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Updater\DynamicEntityUpdaterInterface
     */
    public function createDynamicEntityUpdater(): DynamicEntityUpdaterInterface
    {
        return new DynamicEntityUpdater(
            $this->createDynamicEntityReader(),
            $this->createDynamicEntityWriter(),
            $this->createDynamicEntityUpdateValidator(),
            $this->createDynamicEntityConfigurationTreeValidator(),
            $this->createDynamicEntityCollectionRequestBuilder(),
            $this->createDynamicEntityErrorPathResolver(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface
     */
    public function createDynamicEntityWriter(): DynamicEntityWriterInterface
    {
        return new DynamicEntityWriter(
            $this->getEntityManager(),
            $this->getConnection(),
            $this->createDynamicEntityMapper(),
            $this->getDynamicEntityPostCreatePlugins(),
            $this->getDynamicEntityPostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityCollectionRequestBuilderInterface
     */
    public function createDynamicEntityCollectionRequestBuilder(): DynamicEntityCollectionRequestBuilderInterface
    {
        return new DynamicEntityCollectionRequestBuilder(
            $this->createDynamicEntityMapper(),
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
            $this->createEditableRelationValidator(),
            $this->createRequestFieldValidator(),
            $this->createRequiredFieldValidator(),
            $this->createIntegerFieldTypeValidator(),
            $this->createStringFieldTypeValidator(),
            $this->createBooleanFieldTypeValidator(),
            $this->createDecimalFeildTypeValidator(),
            $this->createConstraintValidator(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface>
     */
    public function getDynamicEntityUpdateValidators(): array
    {
        return [
            $this->createEditableRelationValidator(),
            $this->createRequiredFieldValidator(),
            $this->createRequestFieldValidator(),
            $this->createIntegerFieldTypeValidator(),
            $this->createStringFieldTypeValidator(),
            $this->createBooleanFieldTypeValidator(),
            $this->createDecimalFeildTypeValidator(),
            $this->createConstraintValidator(),
        ];
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createRequiredFieldValidator(): DynamicEntityValidatorInterface
    {
        return new RequiredFieldValidator(
            $this->createDynamicEntityErrorPathResolver(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createConstraintValidator(): DynamicEntityValidatorInterface
    {
        return new ConstraintValidator(
            $this->createDynamicEntityErrorPathResolver(),
            $this->getFieldsValidationConstraints(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createRequestFieldValidator(): DynamicEntityValidatorInterface
    {
        return new RequestFieldValidator(
            $this->createDynamicEntityErrorPathResolver(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    public function createEditableRelationValidator(): DynamicEntityValidatorInterface
    {
        return new EditableRelationValidator();
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
            $this->createFieldMappingValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Installer\Validator\FieldMappingValidatorInterface
     */
    public function createFieldMappingValidator(): FieldMappingValidatorInterface
    {
        return new FieldMappingValidator(
            $this->getPropelDatabaseMap(),
        );
    }

    /**
     * @return \Propel\Runtime\Map\DatabaseMap
     */
    public function getPropelDatabaseMap(): DatabaseMap
    {
        return Propel::getDatabaseMap();
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
            $this->createDisallowedTablesReader(),
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
     * @return \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface
     */
    public function createDynamicEntityMapper(): DynamicEntityMapperInterface
    {
        return new DynamicEntityMapper(
            $this->createPostEditRequestExpander(),
        );
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

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Reader\DisallowedTablesReaderInterface
     */
    public function createDisallowedTablesReader(): DisallowedTablesReaderInterface
    {
        return new DisallowedTablesReader(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityRelationConfigurationTreeBuilderInterface
     */
    public function createDynamicEntityRelationConfigurationTreeBuilder(): DynamicEntityRelationConfigurationTreeBuilderInterface
    {
        return new DynamicEntityRelationConfigurationTreeBuilder();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface
     */
    public function createDynamicEntityConfigurationTreeValidator(): DynamicEntityConfigurationTreeValidatorInterface
    {
        return new DynamicEntityConfigurationTreeValidator(
            $this->createDynamicEntityMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface
     */
    public function createDynamicEntityIndexer(): DynamicEntityIndexerInterface
    {
        return new DynamicEntityIndexer();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Expander\DynamicEntityPostEditRequestExpanderInterface
     */
    public function createPostEditRequestExpander(): DynamicEntityPostEditRequestExpanderInterface
    {
        return new DynamicEntityPostEditRequestExpander(
            $this->createDynamicEntityIndexer(),
        );
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface
     */
    public function createDynamicEntityErrorPathResolver(): DynamicEntityErrorPathResolverInterface
    {
        return new DynamicEntityErrorPathResolver();
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\Constraint\ConstraintInterface
     */
    public function createUrlConstraint(): ConstraintInterface
    {
        return new UrlConstraint();
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntity\Business\Validator\Field\Completeness\Constraint\ConstraintInterface>
     */
    public function getFieldsValidationConstraints(): array
    {
        return [
            $this->createUrlConstraint(),
        ];
    }
}
