<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\MerchantApp\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantAppOnboardingBuilder;
use Generated\Shared\DataBuilder\MerchantAppOnboardingStatusBuilder;
use Generated\Shared\DataBuilder\MerchantAppOnboardingStatusChangedBuilder;
use Generated\Shared\DataBuilder\MerchantOnboardingContentBuilder;
use Generated\Shared\DataBuilder\ReadyForMerchantAppOnboardingBuilder;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboarding;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingQuery;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatusQuery;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class MerchantAppHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer
     */
    public function haveReadyForMerchantAppOnboardingTransfer(array $seed = []): ReadyForMerchantAppOnboardingTransfer
    {
        return (new ReadyForMerchantAppOnboardingBuilder($seed))
            ->withOnboarding($seed)
            ->withAdditionalLink()
            ->withAnotherAdditionalLink()
            ->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer
     */
    public function haveMerchantAppOnboardingStatusChangedTransfer(array $seed = []): MerchantAppOnboardingStatusChangedTransfer
    {
        return (new MerchantAppOnboardingStatusChangedBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingTransfer
     */
    public function haveMerchantAppOnboarding(array $seed = []): MerchantAppOnboardingTransfer
    {
        $additionalContentBuilder = (new MerchantOnboardingContentBuilder())->withAdditionalLink()->withAnotherAdditionalLink();

        return (new MerchantAppOnboardingBuilder($seed))->withOnboarding($seed)->withAdditionalContent($additionalContentBuilder)->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingTransfer
     */
    public function haveMerchantAppOnboardingPersisted(array $seed = []): MerchantAppOnboardingTransfer
    {
        $merchantAppOnboardingTransfer = $this->haveMerchantAppOnboarding($seed);
        $merchantAppOnboardingEntity = new SpyMerchantAppOnboarding();
        $merchantAppOnboardingData = $merchantAppOnboardingTransfer->toArray();
        $merchantAppOnboardingData['additional_content'] = json_encode($merchantAppOnboardingData['additional_content']);
        $merchantAppOnboardingEntity
            ->fromArray($merchantAppOnboardingData)
            ->setOnboardingUrl($merchantAppOnboardingTransfer->getOnboarding()->getUrl())
            ->setOnboardingStrategy($merchantAppOnboardingTransfer->getOnboarding()->getStrategy());

        $merchantAppOnboardingEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($merchantAppOnboardingEntity): void {
            $merchantAppOnboardingEntity->delete();
        });

        return $merchantAppOnboardingTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer
     */
    public function haveMerchantAppOnboardingStatus(array $seed = []): MerchantAppOnboardingStatusTransfer
    {
        return (new MerchantAppOnboardingStatusBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingTransfer
     */
    public function haveMerchantAppOnboardingStatusPersisted(array $seed = []): MerchantAppOnboardingStatusTransfer
    {
        if (!isset($seed['status'])) {
            $seed['status'] = MerchantAppOnboardingStatusInterface::INCOMPLETE;
        }

        $spyMerchantAppOnboardingStatusEntity = new SpyMerchantAppOnboardingStatus();

        if (isset($seed[MerchantAppOnboardingStatusTransfer::MERCHANT_APP_ONBOARDING])) {
            $merchantAppOnboardingSeed = $seed[MerchantAppOnboardingStatusTransfer::MERCHANT_APP_ONBOARDING];
            $merchantAppOnboardingEntity = SpyMerchantAppOnboardingQuery::create()
                ->filterByAppIdentifier($merchantAppOnboardingSeed[MerchantAppOnboardingTransfer::APP_IDENTIFIER])
                ->filterByType($merchantAppOnboardingSeed[MerchantAppOnboardingTransfer::TYPE])
                ->findOne();

            if ($merchantAppOnboardingEntity) {
                $spyMerchantAppOnboardingStatusEntity->setSpyMerchantAppOnboarding($merchantAppOnboardingEntity);
            }
        }

        $merchantAppOnboardingStatusTransfer = $this->haveMerchantAppOnboardingStatus($seed);
        $spyMerchantAppOnboardingStatusEntity->fromArray($merchantAppOnboardingStatusTransfer->toArray());

        $spyMerchantAppOnboardingStatusEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($spyMerchantAppOnboardingStatusEntity): void {
            $spyMerchantAppOnboardingStatusEntity->delete();
        });

        $merchantAppOnboardingStatusTransfer->fromArray($spyMerchantAppOnboardingStatusEntity->toArray(), true);

        return $merchantAppOnboardingStatusTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer
     *
     * @return void
     */
    public function seeMerchantAppOnboardingEntityInDatabase(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void
    {
        $spyMerchantAppOnboardingEntity = SpyMerchantAppOnboardingQuery::create()
            ->filterByType($readyForMerchantAppOnboardingTransfer->getType())
            ->filterByAppName($readyForMerchantAppOnboardingTransfer->getAppName())
            ->findOne();

        $this->assertNotNull($spyMerchantAppOnboardingEntity, 'Expected to find MerchantAppOnboarding entity in the database but it was not found.');
        $this->assertSame($readyForMerchantAppOnboardingTransfer->getAppName(), $spyMerchantAppOnboardingEntity->getAppName());
        $this->assertSame($readyForMerchantAppOnboardingTransfer->getType(), $spyMerchantAppOnboardingEntity->getType());
        $this->assertSame($readyForMerchantAppOnboardingTransfer->getOnboarding()->getUrl(), $spyMerchantAppOnboardingEntity->getOnboardingUrl());
        $this->assertJson($spyMerchantAppOnboardingEntity->getAdditionalContent());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer
     * @param string $expectedStatus
     * @param string|null $additionalInfo
     *
     * @return void
     */
    public function seeMerchantAppOnboardingStatusEntityInDatabase(
        MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatusTransfer,
        string $expectedStatus = MerchantAppOnboardingStatusInterface::INCOMPLETE,
        ?string $additionalInfo = null
    ): void {
        $spyMerchantAppOnboardingStatusEntity = SpyMerchantAppOnboardingStatusQuery::create()
            ->filterByMerchantReference($merchantAppOnboardingStatusTransfer->getMerchantReference())
            ->findOne();

        $this->assertNotNull($spyMerchantAppOnboardingStatusEntity, 'Expected to find MerchantAppOnboardingStatus entity in the database but it was not found.');
        $this->assertSame($expectedStatus, $spyMerchantAppOnboardingStatusEntity->getStatus(), sprintf(
            'Expected to find MerchantAppOnboardingStatus with onboardingStatus "%s" but status is "%s".',
            $expectedStatus,
            $spyMerchantAppOnboardingStatusEntity->getStatus(),
        ));

        if ($additionalInfo) {
            $this->assertSame($additionalInfo, $spyMerchantAppOnboardingStatusEntity->getAdditionalInfo(), sprintf(
                'Expected to find MerchantAppOnboardingStatus with additionalInfo "%s" but status is "%s".',
                $additionalInfo,
                $spyMerchantAppOnboardingStatusEntity->getAdditionalInfo(),
            ));
        }
    }
}
