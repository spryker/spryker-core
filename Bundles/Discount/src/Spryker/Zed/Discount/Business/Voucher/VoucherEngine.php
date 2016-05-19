<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Voucher;

use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\VoucherCreateInfoTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\DiscountConfigInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

/**
 * Class VoucherEngine
 */
class VoucherEngine
{

    /**
     * @var int|null
     */
    protected $remainingCodesToGenerate = null;

    /**
     * @var \Spryker\Zed\Discount\DiscountConfigInterface
     */
    protected $settings;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @param \Spryker\Zed\Discount\DiscountConfigInterface $settings
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface $messengerFacade
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     */
    public function __construct(
        DiscountConfigInterface $settings,
        DiscountQueryContainerInterface $queryContainer,
        DiscountToMessengerInterface $messengerFacade,
        ConnectionInterface $connection
    ) {
        $this->settings = $settings;
        $this->queryContainer = $queryContainer;
        $this->messengerFacade = $messengerFacade;
        $this->connection = $connection;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function createVoucherCodes(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $voucherPoolEntity = $this->queryContainer
            ->queryVoucherPool()
            ->findPk($discountVoucherTransfer->getFkDiscountVoucherPool());

        $nextVoucherBatchValue = $this->getNextBatchValueForVouchers($discountVoucherTransfer);

        $discountVoucherTransfer->setVoucherBatch($nextVoucherBatchValue);
        $discountVoucherTransfer->setIncludeTemplate(true);

        $voucherPoolEntity->setTemplate($discountVoucherTransfer->getCustomCode());

        return $this->saveBatchVoucherCodes($voucherPoolEntity, $discountVoucherTransfer);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $voucherPoolEntity
     * @param DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    protected function saveBatchVoucherCodes(SpyDiscountVoucherPool $voucherPoolEntity, DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $this->connection->beginTransaction();
        $voucherCodesAreValid = $this->generateAndSaveVoucherCodes(
            $voucherPoolEntity,
            $discountVoucherTransfer,
            $discountVoucherTransfer->getQuantity()
        );

        return $this->acceptVoucherCodesTransaction($voucherCodesAreValid);
    }

    /**
     * @param \Generated\Shared\Transfer\VoucherCreateInfoTransfer $voucherCreateInfoInterface
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    protected function acceptVoucherCodesTransaction(VoucherCreateInfoTransfer $voucherCreateInfoInterface)
    {
        if ($voucherCreateInfoInterface->getType() === DiscountConstants::MESSAGE_TYPE_SUCCESS) {
            $this->connection->commit();

            return $voucherCreateInfoInterface;
        }

        $this->connection->rollBack();

        return $voucherCreateInfoInterface;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $discountVoucherPool
     * @param DiscountVoucherTransfer $discountVoucherTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    protected function generateAndSaveVoucherCodes(SpyDiscountVoucherPool $discountVoucherPool, DiscountVoucherTransfer $discountVoucherTransfer, $quantity)
    {
        $length = $discountVoucherTransfer->getRandomGeneratedCodeLength();
        $codeCollisions = 0;
        $messageCreateInfoTransfer = new VoucherCreateInfoTransfer();

        for ($i = 0; $i < $quantity; $i++) {
            $code = $this->getRandomVoucherCode($length);

            if ($discountVoucherTransfer->getIncludeTemplate()) {
                $code = $this->getCodeWithTemplate($discountVoucherPool, $code);
            }

            if ($this->voucherCodeExists($code) === true) {
                $codeCollisions++;
                continue;
            }

            $discountVoucherTransfer->setCode($code);

            $this->createVoucherCode($discountVoucherTransfer);
        }

        if ($codeCollisions === 0) {
            $messageCreateInfoTransfer->setType(DiscountConstants::MESSAGE_TYPE_SUCCESS);
            $messageCreateInfoTransfer->setMessage('Voucher codes successfully generated');

            return $messageCreateInfoTransfer;
        }

        if ($codeCollisions === $discountVoucherTransfer->getQuantity()) {
            $messageCreateInfoTransfer->setType(DiscountConstants::MESSAGE_TYPE_ERROR);
            $messageCreateInfoTransfer->setMessage('No available codes to generate');

            return $messageCreateInfoTransfer;
        }

        if ($codeCollisions === $this->remainingCodesToGenerate) {
            $messageCreateInfoTransfer->setType(DiscountConstants::MESSAGE_TYPE_ERROR);
            $messageCreateInfoTransfer->setMessage('No available codes to generate. Select higher code length');

            return $messageCreateInfoTransfer;
        }

        $this->remainingCodesToGenerate = $codeCollisions;

        return $this->generateAndSaveVoucherCodes($discountVoucherPool, $discountVoucherTransfer, $codeCollisions);
    }

    /**
     * @param string $voucherCode
     *
     * @return bool
     */
    protected function voucherCodeExists($voucherCode)
    {
        $voucherCodeEntity = $this->queryContainer
            ->queryDiscountVoucher()
            ->findOneByCode($voucherCode);

        return $voucherCodeEntity !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucher
     */
    public function createVoucherCode(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $voucherEntity = new SpyDiscountVoucher();
        $voucherEntity->fromArray($discountVoucherTransfer->toArray());

        $voucherEntity
            ->setFkDiscountVoucherPool($discountVoucherTransfer->getFkDiscountVoucherPool())
            ->setIsActive(true)
            ->save();

        return $voucherEntity;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected function getRandomVoucherCode($length)
    {
        $allowedCharacters = $this->settings->getVoucherCodeCharacters();

        $consonants = $allowedCharacters[DiscountConstants::KEY_VOUCHER_CODE_CONSONANTS];
        $vowels = $allowedCharacters[DiscountConstants::KEY_VOUCHER_CODE_VOWELS];
        $numbers = $allowedCharacters[DiscountConstants::KEY_VOUCHER_CODE_NUMBERS];

        $code = '';

        while (strlen($code) < $length) {
            if (count($consonants)) {
                $code .= $consonants[random_int(0, count($consonants) - 1)];
            }

            if (count($vowels)) {
                $code .= $vowels[random_int(0, count($vowels) - 1)];
            }

            if (count($numbers)) {
                $code .= $numbers[random_int(0, count($numbers) - 1)];
            }
        }

        return substr($code, 0, $length);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $voucherPoolEntity
     * @param string $code
     *
     * @return string
     */
    protected function getCodeWithTemplate(SpyDiscountVoucherPool $voucherPoolEntity, $code)
    {
        $template = $voucherPoolEntity->getTemplate();
        $replacementString = $this->settings->getVoucherPoolTemplateReplacementString();

        if (!$template) {
            return $code;
        }

        if (!strstr($template, $replacementString)) {
            return $voucherPoolEntity->getTemplate() . $code;
        }

        return str_replace($this->settings->getVoucherPoolTemplateReplacementString(), $code, $template);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @return int
     */
    protected function getNextBatchValueForVouchers(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $nextVoucherBatchValue = 0;

        if ($discountVoucherTransfer->getQuantity() < 2) {
            return $nextVoucherBatchValue;
        }

        $highestBatchValueOnVouchers = $this->queryContainer
            ->queryDiscountVoucher()
            ->filterByFkDiscountVoucherPool($discountVoucherTransfer->getFkDiscountVoucherPool())
            ->orderByVoucherBatch(Criteria::DESC)
            ->findOne();

        if ($highestBatchValueOnVouchers === null) {
            return 1;
        }

        return $highestBatchValueOnVouchers->getVoucherBatch() + 1;
    }

}
