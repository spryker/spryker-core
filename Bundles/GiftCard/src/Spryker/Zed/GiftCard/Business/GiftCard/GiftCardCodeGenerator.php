<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Spryker\Zed\GiftCard\GiftCardConfig;

class GiftCardCodeGenerator implements GiftCardCodeGeneratorInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface
     */
    protected $giftCardReader;

    /**
     * @var \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardCodeCandidateValidationPluginInterface[]
     */
    protected $codeCandidateValidatorPlugins;

    /**
     * @var \Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected $giftCardConfig;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     * @param \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardCodeCandidateValidationPluginInterface[] $codeCandidateValidatorPlugins
     * @param \Spryker\Zed\GiftCard\GiftCardConfig $giftCardConfig
     */
    public function __construct(
        GiftCardReaderInterface $giftCardReader,
        array $codeCandidateValidatorPlugins,
        GiftCardConfig $giftCardConfig
    ) {
        $this->giftCardReader = $giftCardReader;
        $this->giftCardConfig = $giftCardConfig;
        $this->codeCandidateValidatorPlugins = $codeCandidateValidatorPlugins;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    public function generateGiftCardCode($pattern)
    {
        do {
            $candidate = $this->generateGiftCardCodeCandidate($pattern);
        } while ($this->giftCardReader->isPresent($candidate));

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
            '{randomPart}' => $this->getValidRandomString(
                $this->giftCardConfig->getCodeRandomPartLength()
            ),
            '{suffix}' => $this->giftCardConfig->getCodeSuffix(),
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
            $result = $this->generateRandomString(
                $this->giftCardConfig->getCodeRandomPartLength()
            );
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
        $max = $this->getStringLengthInBites($digitSpace) - 1;

        for ($i = 0; $i < $length; $i++) {
            $result .= $digitSpace[random_int(0, $max)];
        }

        return $result;
    }

    /**
     * Note: Prevents wrong results from strlen (because of mbstring.func_overload)
     *
     * @param string $string
     *
     * @return int
     */
    private function getStringLengthInBites($string)
    {
        return mb_strlen($string, '8bit');
    }

    /**
     * @param string $codeCandidate
     *
     * @return bool
     */
    protected function isValid($codeCandidate)
    {
        $result = true;

        foreach ($this->codeCandidateValidatorPlugins as $codeCandidateValidationPlugin) {
            $result &= $codeCandidateValidationPlugin->isValid($codeCandidate);
        }

        return (bool)$result;
    }
}
