<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Spryker\Zed\GiftCard\GiftCardConfig;
use Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface;

class GiftCardCodeGenerator
{

    /**
     * @var \Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected $giftCardConfig;

    /**
     * @var \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface
     */
    private $giftCardQueryContainer;

    /**
     * @param \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface $giftCardQueryContainer
     * @param \Spryker\Zed\GiftCard\GiftCardConfig $giftCardConfig
     */
    public function __construct(
        GiftCardQueryContainerInterface $giftCardQueryContainer,
        GiftCardConfig $giftCardConfig
    ) {
        $this->giftCardConfig = $giftCardConfig;
        $this->giftCardQueryContainer = $giftCardQueryContainer;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    public function generateGiftCardCode($pattern)
    {
        $candidate = $this->generateGiftCardCodeCandidate($pattern);

        //TODO evaluate max tries
        //TODO make sure to prevent gift card / voucher code collisions

        while ($this->hasGiftCardCode($candidate)) {
            $candidate = $this->generateGiftCardCodeCandidate($abstractConfiguration->getCodePattern());
        }

        return $candidate;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function generateGiftCardCodeCandidate($pattern)
    {
        $candidate = $pattern;
        $replacements = $this->getReplacements();

        foreach ($replacements as $pattern => $replacement) {
            $candidate = str_replace($pattern, $replacement, $candidate);
        }

        return mb_strtoupper($candidate);
    }

    /**
     * @return array
     */
    protected function getReplacements()
    {
        return $replacements = [
            '{prefix}' => $this->giftCardConfig->getCodePrefix(),
            '{randomPart}' => $this->getValidRandomString(8),
            //TODO don't hardcode 8, don't hardcode suffix
            '{suffix}' => date('y'),
        ];
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected function getValidRandomString($length)
    {
        $result = $this->generateRandomString($length);

        while (!$this->isValid($result)) {
            $result = $this->generateRandomString(8);
        }

        return $result;
    }

    /**
     * @param int $length
     * @param string $digitSpace
     *
     * @return string
     */
    protected function generateRandomString($length, $digitSpace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $result = '';
        $max = mb_strlen($digitSpace, '8bit') - 1;
        for ($i = 0; $i < $length; $i++) {
            $result .= $digitSpace[random_int(0, $max)];
        }

        return $result;
    }

    /**
     * @param string $codeCandidate
     *
     * @return bool
     */
    protected function isValid($codeCandidate)
    {
        //TODO inject custom code validation plugins

        return true;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function hasGiftCardCode($code)
    {
        return $this->giftCardQueryContainer->queryGiftCardByCode($code)->count() > 0;
    }

}
