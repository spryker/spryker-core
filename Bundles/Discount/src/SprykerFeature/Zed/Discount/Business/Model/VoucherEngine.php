<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Zed\Discount\DiscountConfigInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;

/**
 * Class VoucherEngine
 */
class VoucherEngine
{

    /**
     * @var DiscountConfigInterface
     */
    protected $settings;

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @param DiscountConfigInterface $settings
     * @param DiscountQueryContainer $queryContainer
     */
    public function __construct(DiscountConfigInterface $settings, DiscountQueryContainer $queryContainer)
    {
        $this->settings = $settings;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $amount
     * @param int $idVoucherPool
     * @param bool $includeTemplate
     */
    public function createVoucherCodes($amount, $idVoucherPool, $includeTemplate = true)
    {
        $codeCollisions = 0;
        $voucherPoolEntity = $this->queryContainer->queryVoucherPool()->findPk($idVoucherPool);
        $length = $this->settings->getVoucherCodeLength();

        for ($i = 0; $i < $amount; $i++) {
            try {
                $code = $this->getRandomVoucherCode($length);

                if ($includeTemplate) {
                    $code = $this->getCodeWithTemplate($voucherPoolEntity, $code);
                }

                $this->createVoucherCode($code, $idVoucherPool);
            } catch (\Exception $e) {
                $codeCollisions++;
            }
        }

        if ($codeCollisions > 0) {
            $this->createVoucherCodes($codeCollisions, $idVoucherPool, $includeTemplate);
        }
    }

    /**
     * @param string $code
     * @param int $idVoucherPool
     *
     * @return SpyDiscountVoucher
     */
    public function createVoucherCode($code, $idVoucherPool)
    {
        $voucherEntity = new SpyDiscountVoucher();
        $voucherEntity
            ->setFkDiscountVoucherPool($idVoucherPool)
            ->setIsActive(true)
            ->setCode($code)
            ->save()
        ;

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
        $specialCharacters = $allowedCharacters[DiscountConfigInterface::KEY_VOUCHER_CODE_SPECIAL_CHARACTERS];

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

            if (count($specialCharacters)) {
                $code .= $specialCharacters[rand(0, count($specialCharacters) - 1)];
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
            return $code;
        }

        return str_replace($this->settings->getVoucherPoolTemplateReplacementString(), $code, $template);
    }

}
