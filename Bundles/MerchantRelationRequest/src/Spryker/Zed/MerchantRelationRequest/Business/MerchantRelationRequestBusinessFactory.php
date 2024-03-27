<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationRequest\Business\Creator\AssigneeCompanyBusinessUnitCreator;
use Spryker\Zed\MerchantRelationRequest\Business\Creator\AssigneeCompanyBusinessUnitCreatorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationRequestCreator;
use Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationRequestCreatorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationshipCreator;
use Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationshipCreatorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Deleter\MerchantRelationRequestDeleter;
use Spryker\Zed\MerchantRelationRequest\Business\Deleter\MerchantRelationRequestDeleterInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Expander\MerchantRelationRequestExpander;
use Spryker\Zed\MerchantRelationRequest\Business\Expander\MerchantRelationRequestExpanderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractor;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\ErrorExtractor;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\ErrorExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractor;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilter;
use Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilterInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantReader;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReader;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationshipReader;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Sender\RequestStatusChangeMailNotificationSender;
use Spryker\Zed\MerchantRelationRequest\Business\Sender\RequestStatusChangeMailNotificationSenderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\AssigneeCompanyBusinessUnitUpdater;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\AssigneeCompanyBusinessUnitUpdaterInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\MerchantRelationRequestUpdater;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\MerchantRelationRequestUpdaterInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\RequestApprovalUpdateStrategy;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\RequestCancelationUpdateStrategy;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\RequestPendingUpdateStrategy;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\RequestRejectionUpdateStrategy;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidator;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\ActiveMerchantWithApprovedAccessValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\CompanyAccountCompatibilityValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\CreateMerchantRelationRequestPermissionValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\DecisionNoteLengthValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\EmptyDecisionNoteValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsAllowedToUpdateToPendingValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsApprovableRequestValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsCancelableRequestValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsRejectableRequestValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\NotEmptyAssigneeBusinessUnitsInRequestValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\PendingRequestStatusValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\RequestNoteLengthValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\UniqueAssigneeBusinessUnitsInRequestValidatorRule;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdder;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyUserFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMailFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToPermissionFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface getRepository()
 */
class MerchantRelationRequestBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface
     */
    public function createMerchantRelationRequestReader(): MerchantRelationRequestReaderInterface
    {
        return new MerchantRelationRequestReader(
            $this->getRepository(),
            $this->createMerchantRelationRequestExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Expander\MerchantRelationRequestExpanderInterface
     */
    public function createMerchantRelationRequestExpander(): MerchantRelationRequestExpanderInterface
    {
        return new MerchantRelationRequestExpander(
            $this->getRepository(),
            $this->createMerchantRelationshipReader(),
            $this->createMerchantRelationRequestExtractor(),
            $this->getMerchantRelationRequestExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationRequestCreatorInterface
     */
    public function createMerchantRelationRequestCreator(): MerchantRelationRequestCreatorInterface
    {
        return new MerchantRelationRequestCreator(
            $this->getEntityManager(),
            $this->createMerchantRelationRequestCreateValidator(),
            $this->createMerchantRelationRequestFilter(),
            $this->createAssigneeCompanyBusinessUnitCreator(),
            $this->getMerchantRelationRequestPostCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Updater\MerchantRelationRequestUpdaterInterface
     */
    public function createMerchantRelationRequestUpdater(): MerchantRelationRequestUpdaterInterface
    {
        return new MerchantRelationRequestUpdater(
            $this->createMerchantRelationRequestUpdateValidator(),
            $this->createMerchantRelationRequestFilter(),
            $this->createAssigneeCompanyBusinessUnitUpdater(),
            $this->getMerchantRelationRequestUpdateStrategies(),
            $this->getMerchantRelationRequestPostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Updater\AssigneeCompanyBusinessUnitUpdaterInterface
     */
    public function createAssigneeCompanyBusinessUnitUpdater(): AssigneeCompanyBusinessUnitUpdaterInterface
    {
        return new AssigneeCompanyBusinessUnitUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createAssigneeCompanyBusinessUnitExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface
     */
    public function createAssigneeCompanyBusinessUnitExtractor(): AssigneeCompanyBusinessUnitExtractorInterface
    {
        return new AssigneeCompanyBusinessUnitExtractor();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Creator\AssigneeCompanyBusinessUnitCreatorInterface
     */
    public function createAssigneeCompanyBusinessUnitCreator(): AssigneeCompanyBusinessUnitCreatorInterface
    {
        return new AssigneeCompanyBusinessUnitCreator(
            $this->getEntityManager(),
            $this->createAssigneeCompanyBusinessUnitExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Deleter\MerchantRelationRequestDeleterInterface
     */
    public function createMerchantRelationRequestDeleter(): MerchantRelationRequestDeleterInterface
    {
        return new MerchantRelationRequestDeleter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createMerchantRelationRequestExtractor(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Filter\MerchantRelationRequestFilterInterface
     */
    public function createMerchantRelationRequestFilter(): MerchantRelationRequestFilterInterface
    {
        return new MerchantRelationRequestFilter(
            $this->createErrorExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Extractor\ErrorExtractorInterface
     */
    public function createErrorExtractor(): ErrorExtractorInterface
    {
        return new ErrorExtractor();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface
     */
    public function createMerchantRelationRequestCreateValidator(): MerchantRelationRequestValidatorInterface
    {
        return new MerchantRelationRequestValidator(
            $this->getMerchantRelationRequestCreateValidatorRules(),
        );
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface>
     */
    public function getMerchantRelationRequestCreateValidatorRules(): array
    {
        return [
            $this->createPendingRequestStatusValidatorRule(),
            $this->createNotEmptyAssigneeBusinessUnitsInRequestValidatorRule(),
            $this->createEmptyDecisionNoteValidatorRule(),
            $this->createRequestNoteLengthValidatorRule(),
            $this->createCreateMerchantRelationRequestPermissionValidatorRule(),
            $this->createUniqueAssigneeBusinessUnitsInRequestValidatorRule(),
            $this->createActiveMerchantWithApprovedAccessValidatorRule(),
            $this->createCompanyAccountCompatibilityValidatorRule(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface>
     */
    public function getMerchantRelationRequestUpdateStrategies(): array
    {
        return [
            $this->createRequestApprovalUpdateStrategy(),
            $this->createRequestCancelationUpdateStrategy(),
            $this->createRequestRejectionUpdateStrategy(),
            $this->createRequestPendingUpdateStrategy(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\MerchantRelationRequestValidatorInterface
     */
    public function createMerchantRelationRequestUpdateValidator(): MerchantRelationRequestValidatorInterface
    {
        return new MerchantRelationRequestValidator(
            $this->getMerchantRelationRequestUpdateValidatorRules(),
        );
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface>
     */
    public function getMerchantRelationRequestUpdateValidatorRules(): array
    {
        return [
            $this->createDecisionNoteLengthValidatorRule(),
            $this->createIsApprovableRequestValidatorRule(),
            $this->createIsCancelableRequestValidatorRule(),
            $this->createIsRejectableRequestValidatorRule(),
            $this->createIsAllowedToUpdateToPendingValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createCompanyAccountCompatibilityValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new CompanyAccountCompatibilityValidatorRule(
            $this->createErrorAdder(),
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyUserFacade(),
            $this->createAssigneeCompanyBusinessUnitExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createEmptyDecisionNoteValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new EmptyDecisionNoteValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createNotEmptyAssigneeBusinessUnitsInRequestValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new NotEmptyAssigneeBusinessUnitsInRequestValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createPendingRequestStatusValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new PendingRequestStatusValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationshipReaderInterface
     */
    public function createMerchantRelationshipReader(): MerchantRelationshipReaderInterface
    {
        return new MerchantRelationshipReader(
            $this->getMerchantRelationshipFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createRequestNoteLengthValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new RequestNoteLengthValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createUniqueAssigneeBusinessUnitsInRequestValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new UniqueAssigneeBusinessUnitsInRequestValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface
     */
    public function createErrorAdder(): ErrorAdderInterface
    {
        return new ErrorAdder();
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface>
     */
    public function getMerchantRelationRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::PLUGINS_MERCHANT_RELATION_REQUEST_EXPANDER);
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostCreatePluginInterface>
     */
    public function getMerchantRelationRequestPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::PLUGINS_MERCHANT_RELATION_REQUEST_POST_CREATE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface
     */
    public function createRequestApprovalUpdateStrategy(): MerchantRelationRequestUpdaterStrategyInterface
    {
        return new RequestApprovalUpdateStrategy(
            $this->getEntityManager(),
            $this->createMerchantRelationRequestReader(),
            $this->getConfig(),
            $this->createMerchantRelationshipCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationshipCreatorInterface
     */
    public function createMerchantRelationshipCreator(): MerchantRelationshipCreatorInterface
    {
        return new MerchantRelationshipCreator(
            $this->getMerchantRelationshipFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface
     */
    public function createRequestCancelationUpdateStrategy(): MerchantRelationRequestUpdaterStrategyInterface
    {
        return new RequestCancelationUpdateStrategy(
            $this->getEntityManager(),
            $this->createMerchantRelationRequestReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface
     */
    public function createRequestRejectionUpdateStrategy(): MerchantRelationRequestUpdaterStrategyInterface
    {
        return new RequestRejectionUpdateStrategy(
            $this->getEntityManager(),
            $this->createMerchantRelationRequestReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface
     */
    public function createRequestPendingUpdateStrategy(): MerchantRelationRequestUpdaterStrategyInterface
    {
        return new RequestPendingUpdateStrategy(
            $this->getEntityManager(),
            $this->createMerchantRelationRequestReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createDecisionNoteLengthValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new DecisionNoteLengthValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createIsApprovableRequestValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new IsApprovableRequestValidatorRule(
            $this->createErrorAdder(),
            $this->getConfig(),
            $this->createMerchantRelationRequestReader(),
            $this->createAssigneeCompanyBusinessUnitExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createIsCancelableRequestValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new IsCancelableRequestValidatorRule(
            $this->createErrorAdder(),
            $this->getConfig(),
            $this->createMerchantRelationRequestReader(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createCreateMerchantRelationRequestPermissionValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new CreateMerchantRelationRequestPermissionValidatorRule(
            $this->createErrorAdder(),
            $this->getPermissionFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createActiveMerchantWithApprovedAccessValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new ActiveMerchantWithApprovedAccessValidatorRule(
            $this->createErrorAdder(),
            $this->createMerchantReader(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getMerchantFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createIsRejectableRequestValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new IsRejectableRequestValidatorRule(
            $this->createErrorAdder(),
            $this->getConfig(),
            $this->createMerchantRelationRequestReader(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    public function createIsAllowedToUpdateToPendingValidatorRule(): MerchantRelationValidatorRuleInterface
    {
        return new IsAllowedToUpdateToPendingValidatorRule(
            $this->createErrorAdder(),
            $this->getConfig(),
            $this->createMerchantRelationRequestReader(),
            $this->createCompanyAccountCompatibilityValidatorRule(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractorInterface
     */
    public function createMerchantRelationRequestExtractor(): MerchantRelationRequestExtractorInterface
    {
        return new MerchantRelationRequestExtractor();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Sender\RequestStatusChangeMailNotificationSenderInterface
     */
    public function createRequestStatusChangeMailNotificationSender(): RequestStatusChangeMailNotificationSenderInterface
    {
        return new RequestStatusChangeMailNotificationSender($this->getMailFacade(), $this->getConfig());
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostUpdatePluginInterface>
     */
    public function getMerchantRelationRequestPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::PLUGINS_MERCHANT_RELATION_REQUEST_POST_UPDATE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationRequestToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): MerchantRelationRequestToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): MerchantRelationRequestToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToPermissionFacadeInterface
     */
    public function getPermissionFacade(): MerchantRelationRequestToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::FACADE_PERMISSION);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantRelationRequestToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMailFacadeInterface
     */
    public function getMailFacade(): MerchantRelationRequestToMailFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::FACADE_MAIL);
    }
}
