<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Filter;

use Generated\Shared\Transfer\NumberFormatFilterTransfer;
use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;
use Spryker\Zed\Gui\Dependency\Service\GuiToUtilNumberServiceInterface;
use Twig\TwigFilter;

class NumberFormatterTwigFilterFactory implements NumberFormatterTwigFilterFactoryInterface
{
    /**
     * @var string
     */
    protected const FORMAT_INT_FILTER_NAME = 'formatInt';

    /**
     * @var string
     */
    protected const FORMAT_FLOAT_FILTER_NAME = 'formatFloat';

    /**
     * @var \Spryker\Zed\Gui\Dependency\Service\GuiToUtilNumberServiceInterface
     */
    protected GuiToUtilNumberServiceInterface $utilNumberService;

    /**
     * @param \Spryker\Zed\Gui\Dependency\Service\GuiToUtilNumberServiceInterface $utilNumberService
     */
    public function __construct(GuiToUtilNumberServiceInterface $utilNumberService)
    {
        $this->utilNumberService = $utilNumberService;
    }

    /**
     * @return \Twig\TwigFilter
     */
    public function createFormatIntFilter(): TwigFilter
    {
        return new TwigFilter(
            static::FORMAT_INT_FILTER_NAME,
            function (int $value, ?string $locale = null, ?int $numberFormatStyle = null, ?int $maxFractionDigits = null): string {
                return $this->utilNumberService->formatInt(
                    $this->createNumberFormatIntRequest($value, $locale, $numberFormatStyle, $maxFractionDigits),
                );
            },
        );
    }

    /**
     * @return \Twig\TwigFilter
     */
    public function createFormatFloatFilter(): TwigFilter
    {
        return new TwigFilter(
            static::FORMAT_FLOAT_FILTER_NAME,
            function (float $value, ?string $locale = null, ?int $numberFormatStyle = null, ?int $maxFractionDigits = null): string {
                return $this->utilNumberService->formatFloat(
                    $this->createNumberFormatFloatRequest($value, $locale, $numberFormatStyle, $maxFractionDigits),
                );
            },
        );
    }

    /**
     * @param int $value
     * @param string|null $locale
     * @param int|null $numberFormatStyle
     * @param int|null $maxFractionDigits
     *
     * @return \Generated\Shared\Transfer\NumberFormatIntRequestTransfer
     */
    protected function createNumberFormatIntRequest(
        int $value,
        ?string $locale = null,
        ?int $numberFormatStyle = null,
        ?int $maxFractionDigits = null
    ): NumberFormatIntRequestTransfer {
        return (new NumberFormatIntRequestTransfer())
            ->setNumber($value)
            ->setNumberFormatFilter(
                $this->createNumberFormatFilter($locale, $numberFormatStyle, $maxFractionDigits),
            );
    }

    /**
     * @param float $value
     * @param string|null $locale
     * @param int|null $numberFormatStyle
     * @param int|null $maxFractionDigits
     *
     * @return \Generated\Shared\Transfer\NumberFormatFloatRequestTransfer
     */
    protected function createNumberFormatFloatRequest(
        float $value,
        ?string $locale = null,
        ?int $numberFormatStyle = null,
        ?int $maxFractionDigits = null
    ): NumberFormatFloatRequestTransfer {
        return (new NumberFormatFloatRequestTransfer())
            ->setNumber($value)
            ->setNumberFormatFilter(
                $this->createNumberFormatFilter($locale, $numberFormatStyle, $maxFractionDigits),
            );
    }

    /**
     * @param string|null $locale
     * @param int|null $numberFormatStyle
     * @param int|null $maxFractionDigits
     *
     * @return \Generated\Shared\Transfer\NumberFormatFilterTransfer
     */
    protected function createNumberFormatFilter(
        ?string $locale = null,
        ?int $numberFormatStyle = null,
        ?int $maxFractionDigits = null
    ): NumberFormatFilterTransfer {
        return (new NumberFormatFilterTransfer())
            ->setLocale($locale)
            ->setNumberFormatStyle($numberFormatStyle)
            ->setMaxFractionDigits($maxFractionDigits);
    }
}
