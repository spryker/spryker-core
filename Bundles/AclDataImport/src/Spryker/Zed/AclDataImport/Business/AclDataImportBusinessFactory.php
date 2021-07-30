<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport\Business;

use Spryker\Zed\AclDataImport\Business\DataImport\AclGroupDataImportStep\AclGroupRequiredFieldValidatorStep;
use Spryker\Zed\AclDataImport\Business\DataImport\AclGroupDataImportStep\AclGroupWriterStep;
use Spryker\Zed\AclDataImport\Business\DataImport\AclGroupRoleDataImportStep\AclGroupReferenceToIdAclGroupStep;
use Spryker\Zed\AclDataImport\Business\DataImport\AclGroupRoleDataImportStep\AclGroupRoleRequiredFieldValidatorStep;
use Spryker\Zed\AclDataImport\Business\DataImport\AclGroupRoleDataImportStep\AclGroupRoleWriterStep;
use Spryker\Zed\AclDataImport\Business\DataImport\AclGroupRoleDataImportStep\AclRoleReferenceToIdAclRoleStep;
use Spryker\Zed\AclDataImport\Business\DataImport\AclRoleDataImportStep\AclRoleRequiredFieldValidatorStep;
use Spryker\Zed\AclDataImport\Business\DataImport\AclRoleDataImportStep\AclRoleWriterStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\AclDataImport\AclDataImportConfig getConfig()
 */
class AclDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getAclRoleDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getAclRoleDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAclRoleRequiredFieldValidatorStep());
        $dataSetStepBroker->addStep($this->createAclRoleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclRoleWriterStep(): DataImportStepInterface
    {
        return new AclRoleWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getAclGroupDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getAclGroupDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAclGroupRequiredFieldValidatorStep());
        $dataSetStepBroker->addStep($this->createAclGroupWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclGroupWriterStep(): DataImportStepInterface
    {
        return new AclGroupWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getAclGroupRoleDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getAclGroupRoleDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createAclGroupRoleRequiredFieldValidatorStep());
        $dataSetStepBroker->addStep($this->createAclGroupReferenceToIdAclGroupStep());
        $dataSetStepBroker->addStep($this->createAclRoleReferenceToIdAclRoleStep());
        $dataSetStepBroker->addStep($this->createAclGroupRoleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclGroupReferenceToIdAclGroupStep(): DataImportStepInterface
    {
        return new AclGroupReferenceToIdAclGroupStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclRoleReferenceToIdAclRoleStep(): DataImportStepInterface
    {
        return new AclRoleReferenceToIdAclRoleStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclGroupRoleWriterStep(): DataImportStepInterface
    {
        return new AclGroupRoleWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclGroupRequiredFieldValidatorStep(): DataImportStepInterface
    {
        return new AclGroupRequiredFieldValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclRoleRequiredFieldValidatorStep(): DataImportStepInterface
    {
        return new AclRoleRequiredFieldValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createAclGroupRoleRequiredFieldValidatorStep(): DataImportStepInterface
    {
        return new AclGroupRoleRequiredFieldValidatorStep();
    }
}
