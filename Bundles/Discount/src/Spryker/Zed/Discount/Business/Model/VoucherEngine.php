<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\VoucherTransfer;
use Generated\Shared\Transfer\VoucherCreateInfoTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\DiscountConfigInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;

/**
 * Class VoucherEngine
 */
class VoucherEngine
{

    const MESSAGE_TYPE_SUCCESS = 'success';
    const MESSAGE_TYPE_ERROR = 'error';

    protected $remainingCodesToGenerate = null;

    /**
     * @var DiscountConfigInterface
     */
    protected $settings;

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @var DiscountToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param DiscountConfigInterface $settings
     * @param DiscountQueryContainer $queryContainer
     * @param DiscountToMessengerInterface $messengerFacade
     * @param ConnectionInterface $connection
     */
    public function __construct(
        DiscountConfigInterface $settings,
        DiscountQueryContainer $queryContainer,
        DiscountToMessengerInterface $messengerFacade,
        ConnectionInterface $connection
    ) {
        $this->settings = $settings;
        $this->queryContainer = $queryContainer;
        $this->messengerFacade = $messengerFacade;
        $this->connection = $connection;
    }

    /**
     * @param VoucherTransfer $voucherTransfer
     *
     * @return VoucherCreateInfoTransfer
     */
    public function createVoucherCodes(VoucherTransfer $voucherTransfer)
    {
        $voucherPoolEntity = $this->queryContainer
            ->queryVoucherPool()
            ->findPk($voucherTransfer->getFkDiscountVoucherPool());

        $nextVoucherBatchValue = $this->getNextBatchValueForVouchers($voucherTransfer);

        $voucherTransfer->setVoucherBatch($nextVoucherBatchValue);

        $voucherTransfer->setIncludeTemplate(true);
        $voucherPoolEntity->setTemplate($voucherTransfer->getCustomCode());

        return $this->saveBatchVoucherCodes($voucherPoolEntity, $voucherTransfer);
    }

    /**
     * @param SpyDiscountVoucherPool $voucherPoolEntity
     * @param TransferInterface $voucherTransfer
     *
     * @return VoucherCreateInfoTransfer
     */
    protected function saveBatchVoucherCodes(SpyDiscountVoucherPool $voucherPoolEntity, TransferInterface $voucherTransfer)
    {
        $this->connection->beginTransaction();
        $voucherCodesAreValid = $this->generateAndSaveVoucherCodes($voucherPoolEntity, $voucherTransfer, $voucherTransfer->getQuantity());

        return $this->acceptVoucherCodesTransation($voucherCodesAreValid);
    }

    /**
     * @param VoucherCreateInfoTransfer $voucherCreateInfoInterface
     *
     * @return VoucherCreateInfoTransfer
     */
    protected function acceptVoucherCodesTransation(VoucherCreateInfoTransfer $voucherCreateInfoInterface)
    {
        if ($voucherCreateInfoInterface->getType() === self::MESSAGE_TYPE_SUCCESS) {
            $this->connection->commit();

            return $voucherCreateInfoInterface;
        }

        $this->connection->rollBack();

        return $voucherCreateInfoInterface;
    }

    /**
     * @param SpyDiscountVoucherPool $discountVoucherPool
     * @param VoucherTransfer $voucherTransfer
     * @param int $quantiy
     *
     * @return VoucherCreateInfoTransfer
     */
    protected function generateAndSaveVoucherCodes(SpyDiscountVoucherPool $discountVoucherPool, VoucherTransfer $voucherTransfer, $quantiy)
    {
        $length = $voucherTransfer->getCodeLength();
        $codeCollisions = 0;
        $messageCreateInfoTransfer = new VoucherCreateInfoTransfer();

        for ($i = 0; $i < $quantiy; $i++) {
            $code = $this->getRandomVoucherCode($length);

            if ($voucherTransfer->getIncludeTemplate()) {
                $code = $this->getCodeWithTemplate($discountVoucherPool, $code);
            }

            if ($this->voucherCodeExists($code) === true) {
                $codeCollisions++;
                continue;
            }

            $voucherTransfer->setCode($code);

            $this->createVoucherCode($voucherTransfer);
        }

        if ($codeCollisions === 0) {
            $messageCreateInfoTransfer->setType(self::MESSAGE_TYPE_SUCCESS);
            $messageCreateInfoTransfer->setMessage('Voucher codes successfully generated');

            return $messageCreateInfoTransfer;
        }

        if ($codeCollisions === $voucherTransfer->getQuantity()) {
            $messageCreateInfoTransfer->setType(self::MESSAGE_TYPE_ERROR);
            $messageCreateInfoTransfer->setMessage('No available codes to generate');

            return $messageCreateInfoTransfer;
        }

        if ($codeCollisions === $this->remainingCodesToGenerate) {
            $messageCreateInfoTransfer->setType(self::MESSAGE_TYPE_ERROR);
            $messageCreateInfoTransfer->setMessage('No available codes to generate. Select higher code length');

            return $messageCreateInfoTransfer;
        }

        $this->remainingCodesToGenerate = $codeCollisions;

        return $this->generateAndSaveVoucherCodes($discountVoucherPool, $voucherTransfer, $codeCollisions);
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
     * @param VoucherTransfer $voucherTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return SpyDiscountVoucher
     */
    public function createVoucherCode(VoucherTransfer $voucherTransfer)
    {
        $voucherEntity = new SpyDiscountVoucher();
        $voucherEntity->fromArray($voucherTransfer->toArray());

        $voucherEntity
            ->setFkDiscountVoucherPool($voucherTransfer->getFkDiscountVoucherPool())
            ->setIsActive(true)
            ->save();

        return $voucherEntity;
    }

    /**
     * @param int $length
     * @param bool $asMd5
     *
     * @return string
     */
    protected function getRandomVoucherCode($length, $asMd5 = false)
    {
        $allowedCharacters = $this->settings->getVoucherCodeCharacters();
        srand((double) microtime() * 1000000);

        $consonants = $allowedCharacters[DiscountConfigInterface::KEY_VOUCHER_CODE_CONSONANTS];
        $vowels = $allowedCharacters[DiscountConfigInterface::KEY_VOUCHER_CODE_VOWELS];
        $numbers = $allowedCharacters[DiscountConfigInterface::KEY_VOUCHER_CODE_NUMBERS];

        $code = '';

        while (strlen($code) < $length) {
            if (count($consonants)) {
                $code .= $consonants[rand(0, count($consonants) - 1)];
            }

            if (count($vowels)) {
                $code .= $vowels[rand(0, count($vowels) - 1)];
            }

            if (count($numbers)) {
                $code .= $numbers[rand(0, count($numbers) - 1)];
            }
        }

        if ($asMd5) {
            return substr(md5($code), 0, $length);
        }

        return substr($code, 0, $length);
    }

    /**
     * @param SpyDiscountVoucherPool $voucherPoolEntity
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
     * @param VoucherTransfer $voucherTransfer
     *
     * @return int
     */
    protected function getNextBatchValueForVouchers(VoucherTransfer $voucherTransfer)
    {
        $nextVoucherBatchValue = 0;

        if ($voucherTransfer->getQuantity() < 2) {
            return $nextVoucherBatchValue;
        }

        $highestBatchValueOnVouchers = $this->queryContainer
            ->queryDiscountVoucher()
            ->filterByFkDiscountVoucherPool($voucherTransfer->getFkDiscountVoucherPool())
            ->orderByVoucherBatch(Criteria::DESC)
            ->findOne();

        if ($highestBatchValueOnVouchers === null) {
            return 1;
        }

        return $highestBatchValueOnVouchers->getVoucherBatch() + 1;
    }

}
