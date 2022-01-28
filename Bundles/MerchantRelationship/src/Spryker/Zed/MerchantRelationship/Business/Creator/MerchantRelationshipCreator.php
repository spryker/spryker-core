<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Creator;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface;
use Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;

class MerchantRelationshipCreator implements MerchantRelationshipCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $merchantRelationshipEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface
     */
    protected $merchantRelationshipValidator;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface
     */
    protected $merchantRelationshipKeyGenerator;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Creator\MerchantRelationshipCompanyBusinessUnitCreatorInterface
     */
    protected $merchantRelationshipCompanyBusinessUnitCreator;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface>
     */
    protected $merchantRelationshipPostCreatePlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager
     * @param \Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface $merchantRelationshipCreateValidator
     * @param \Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface $merchantRelationshipKeyGenerator
     * @param \Spryker\Zed\MerchantRelationship\Business\Creator\MerchantRelationshipCompanyBusinessUnitCreatorInterface $merchantRelationshipCompanyBusinessUnitCreator
     * @param \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeInterface $merchantFacade
     * @param array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface> $merchantRelationshipPostCreatePlugins
     */
    public function __construct(
        MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager,
        MerchantRelationshipValidatorInterface $merchantRelationshipCreateValidator,
        MerchantRelationshipKeyGeneratorInterface $merchantRelationshipKeyGenerator,
        MerchantRelationshipCompanyBusinessUnitCreatorInterface $merchantRelationshipCompanyBusinessUnitCreator,
        MerchantRelationshipToMerchantFacadeInterface $merchantFacade,
        array $merchantRelationshipPostCreatePlugins
    ) {
        $this->merchantRelationshipEntityManager = $merchantRelationshipEntityManager;
        $this->merchantRelationshipValidator = $merchantRelationshipCreateValidator;
        $this->merchantRelationshipKeyGenerator = $merchantRelationshipKeyGenerator;
        $this->merchantRelationshipCompanyBusinessUnitCreator = $merchantRelationshipCompanyBusinessUnitCreator;
        $this->merchantFacade = $merchantFacade;
        $this->merchantRelationshipPostCreatePlugins = $merchantRelationshipPostCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|\Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    public function create(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ) {
        if ($merchantRelationshipRequestTransfer !== null) {
            return $this->createByMerchantRelationshipRequestTransfer($merchantRelationshipRequestTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipTransfer) {
            return $this->executeCreateTransaction($merchantRelationshipTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    protected function createByMerchantRelationshipRequestTransfer(
        MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer
    ): MerchantRelationshipResponseTransfer {
        $merchantRelationshipTransfer = $merchantRelationshipRequestTransfer->getMerchantRelationshipOrFail();
        $merchantRelationshipResponseTransfer = (new MerchantRelationshipResponseTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer)
            ->setIsSuccessful(true);

        $merchantRelationshipValidationErrorCollectionTransfer = $this->merchantRelationshipValidator->validate(
            $merchantRelationshipTransfer,
            new MerchantRelationshipValidationErrorCollectionTransfer(),
        );

        if ($merchantRelationshipValidationErrorCollectionTransfer->getErrors()->count()) {
            $merchantRelationshipResponseTransfer->setIsSuccessful(false);

            return $merchantRelationshipResponseTransfer->setErrors($merchantRelationshipValidationErrorCollectionTransfer->getErrors());
        }

        $merchantTransfer = $this->merchantFacade->findOne(
            $this->createMerchantCriteriaTransfer($merchantRelationshipTransfer),
        );

        $idCompanyBusinessUnit = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()
            ? $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail()
            : $merchantRelationshipTransfer->getFkCompanyBusinessUnitOrFail();

        $merchantRelationshipTransfer->setFkMerchant($merchantTransfer->getIdMerchantOrFail())
            ->setFkCompanyBusinessUnit($idCompanyBusinessUnit);

        $merchantRelationshipTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipTransfer) {
            return $this->executeCreateTransaction($merchantRelationshipTransfer);
        });

        return $merchantRelationshipResponseTransfer->setMerchantRelationship($merchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function executeCreateTransaction(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer
            ->requireFkMerchant()
            ->requireFkCompanyBusinessUnit();

        if (!$merchantRelationshipTransfer->getMerchantRelationshipKey()) {
            $merchantRelationshipTransfer->setMerchantRelationshipKey(
                $this->merchantRelationshipKeyGenerator->generateMerchantRelationshipKey(),
            );
        }

        $merchantRelationshipTransfer = $this->merchantRelationshipEntityManager->saveMerchantRelationship($merchantRelationshipTransfer);
        $merchantRelationshipTransfer = $this->merchantRelationshipCompanyBusinessUnitCreator->createMerchantRelationshipCompanyBusinessUnitRelations(
            $merchantRelationshipTransfer,
        );

        return $this->executeMerchantRelationshipPostCreatePlugins($merchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function executeMerchantRelationshipPostCreatePlugins(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        foreach ($this->merchantRelationshipPostCreatePlugins as $merchantRelationshipPostCreatePlugin) {
            $merchantRelationshipTransfer = $merchantRelationshipPostCreatePlugin->execute($merchantRelationshipTransfer);
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCriteriaTransfer
     */
    protected function createMerchantCriteriaTransfer(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantCriteriaTransfer
    {
        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
        if ($merchantRelationshipTransfer->getMerchant()) {
            return $merchantCriteriaTransfer->setMerchantReference($merchantRelationshipTransfer->getMerchantOrFail()->getMerchantReference());
        }

        return $merchantCriteriaTransfer->setIdMerchant($merchantRelationshipTransfer->getFkMerchant());
    }
}
