<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport\Business;

use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Zed\AclEntityDataImport\AclEntityDataImportDependencyProvider;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep\AclEntityRulePermissionMaskToBinaryStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep\AclEntityRuleRequiredFieldValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep\AclEntityRuleWriterStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep\AclEntitySegmentReferenceToAclEntitySegmentIdStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep\AclEntitySegmentReferenceValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep\AclRoleReferenceToAclRoleIdStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntityRuleDataImportStep\AclRoleReferenceValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep\AclEntitySegmentConnectorRequiredFieldValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep\AclEntitySegmentConnectorWriterStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep\AclEntitySegmentReferenceToAclEntitySegmentIdStep as ConnectorAclEntitySegmentReferenceToAclEntitySegmentIdStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep\AclEntitySegmentReferenceValidatorStep as ConnectorAclEntitySegmentReferenceValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep\DataEntityValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep\TargetEntityReferenceToFkEntityStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentConnectorDataImportStep\TargetEntityValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentDataImportStep\AclEntitySegmentRequiredFieldValidatorStep;
use Spryker\Zed\AclEntityDataImport\Business\DataImport\AclEntitySegmentDataImportStep\AclEntitySegmentWriterStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\AclEntityDataImport\AclEntityDataImportConfig getConfig()
 */
class AclEntityDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getAclEntityRuleDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getAclEntityRuleDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAclEntityRuleRequiredFieldValidatorStep())
            ->addStep($this->createAclRoleReferenceValidatorStep())
            ->addStep($this->createAclRoleReferenceToAclRoleIdStep())
            ->addStep($this->createAclEntitySegmentReferenceValidatorStep())
            ->addStep($this->createAclEntitySegmentReferenceToAclEntitySegmentIdStep())
            ->addStep($this->createAclEntityRulePermissionMaskToBinaryStep())
            ->addStep($this->createAclEntityRuleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getAclEntitySegmentDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getAclEntitySegmentDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAclEntitySegmentRequiredFieldValidatorStep());
        $dataSetStepBroker->addStep($this->createAclEntitySegmentWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getAclEntitySegmentConnectorDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getAclEntitySegmentConnectorDataImporterConfiguration(),
        );
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAclEntitySegmentConnectorRequiredFieldValidatorStep())
            ->addStep($this->createDataEntityValidatorStep())
            ->addStep($this->createTargetEntityValidatorStep())
            ->addStep($this->createConnectorAclEntitySegmentReferenceValidatorStep())
            ->addStep($this->createTargetEntityReferenceToFkEntityStep())
            ->addStep($this->createConnectorAclEntitySegmentReferenceToIdAclEntitySegmentStep())
            ->addStep($this->createAclEntitySegmentConnectorWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntitySegmentReferenceToAclEntitySegmentIdStep(): DataImportStepInterface
    {
        return new AclEntitySegmentReferenceToAclEntitySegmentIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclRoleReferenceValidatorStep(): DataImportStepInterface
    {
        return new AclRoleReferenceValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntitySegmentReferenceValidatorStep(): DataImportStepInterface
    {
        return new AclEntitySegmentReferenceValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclRoleReferenceToAclRoleIdStep(): DataImportStepInterface
    {
        return new AclRoleReferenceToAclRoleIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntityRuleWriterStep(): DataImportStepInterface
    {
        return new AclEntityRuleWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntitySegmentWriterStep(): DataImportStepInterface
    {
        return new AclEntitySegmentWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntitySegmentConnectorWriterStep(): DataImportStepInterface
    {
        return new AclEntitySegmentConnectorWriterStep($this->getAclEntityService());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createDataEntityValidatorStep(): DataImportStepInterface
    {
        return new DataEntityValidatorStep($this->getAclEntityService());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createTargetEntityValidatorStep(): DataImportStepInterface
    {
        return new TargetEntityValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createTargetEntityReferenceToFkEntityStep(): DataImportStepInterface
    {
        return new TargetEntityReferenceToFkEntityStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConnectorAclEntitySegmentReferenceValidatorStep(): DataImportStepInterface
    {
        return new ConnectorAclEntitySegmentReferenceValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createConnectorAclEntitySegmentReferenceToIdAclEntitySegmentStep(): DataImportStepInterface
    {
        return new ConnectorAclEntitySegmentReferenceToAclEntitySegmentIdStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntityRulePermissionMaskToBinaryStep(): DataImportStepInterface
    {
        return new AclEntityRulePermissionMaskToBinaryStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntityRuleRequiredFieldValidatorStep(): DataImportStepInterface
    {
        return new AclEntityRuleRequiredFieldValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntitySegmentRequiredFieldValidatorStep(): DataImportStepInterface
    {
        return new AclEntitySegmentRequiredFieldValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclEntitySegmentConnectorRequiredFieldValidatorStep(): DataImportStepInterface
    {
        return new AclEntitySegmentConnectorRequiredFieldValidatorStep();
    }

    /**
     * @return \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected function getAclEntityService(): AclEntityServiceInterface
    {
        return $this->getProvidedDependency(AclEntityDataImportDependencyProvider::SERVICE_ACL_ENTITY);
    }
}
