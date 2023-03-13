<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeXss\Dependency\External;

use voku\helper\AntiXSS;

class UtilSanitizeToAntiXssAdapter implements UtilSanitizeToXssSanitizeInterface
{
    /**
     * @var string
     */
    protected const REGEX_TWIG_FUNCTION = '/(?<=\{\{)(?<functions>[\s+]?[\d\w]+[_[\d\w]+]*\((?<arguments>[^\)]+|)\)[\s+]?)(?=\}\})/';

    /**
     * @var string
     */
    protected const REGEX_TWIG_REPLACEMENT_FUNCTION = '/(?<replacementFunction>twig-%s\((?<arguments>[^\)]+|)\))/';

    /**
     * @var string
     */
    protected const REGEX_ARGUMENTS = '/\((?<arguments>[^\)]+|)\)/';

    /**
     * @var string
     */
    protected const GROUP_FUNCTIONS = 'functions';

    /**
     * @var string
     */
    protected const GROUP_ARGUMENTS = 'arguments';

    /**
     * @var string
     */
    protected const GROUP_REPLACEMENT_FUNCTION = 'replacementFunction';

    /**
     * @var \voku\helper\AntiXSS
     */
    protected $antiXss;

    /**
     * @var array<int, string>
     */
    protected array $twigFunctions;

    public function __construct()
    {
        $this->antiXss = new AntiXSS();
        $this->twigFunctions = [];
    }

    /**
     * @param string $text
     * @param array<string> $allowedAttributes
     * @param array<string> $allowedHtmlTags
     *
     * @return string
     */
    public function sanitize(string $text, array $allowedAttributes = [], array $allowedHtmlTags = []): string
    {
        $this->antiXss->removeEvilAttributes($allowedAttributes);
        $this->antiXss->removeEvilHtmlTags($allowedHtmlTags);
        $text = $this->removeTwigFunctions($text);

        $sanitizedText = $this->antiXss->xss_clean($text);

        return $this->restoreTwigFunctions($sanitizedText);
    }

    /**
     * - Searches for Twig function using pattern.
     * - Replaces found Twig functions names with token to sanitize only functions arguments.
     *
     * @exampe {{function(argument)}} becomes {{twig-0(argument)}}
     *
     * @param string $text
     *
     * @return string
     */
    protected function removeTwigFunctions(string $text): string
    {
        preg_match_all(static::REGEX_TWIG_FUNCTION, $text, $matches);

        $this->twigFunctions = $matches[static::GROUP_FUNCTIONS] ?? [];
        $twigFunctionsArguments = $matches[static::GROUP_ARGUMENTS] ?? [];

        foreach ($this->twigFunctions as $key => $twigFunction) {
            $twigFunctionArguments = $twigFunctionsArguments[$key] ?? '';

            $text = str_replace(
                $twigFunction,
                sprintf('twig-%d(%s)', $key, $twigFunctionArguments),
                $text,
            );
        }

        return $text;
    }

    /**
     * - Searches for replacement tokens using pattern.
     * - Replaces found tokens with corresponding Twig function names.
     *
     * @exampe {{twig-0(sanitizedArgument)}} becomes {{function(sanitizedArgument)}}
     *
     * @param string $text
     *
     * @return string
     */
    protected function restoreTwigFunctions(string $text): string
    {
        foreach ($this->twigFunctions as $key => $twigFunction) {
            preg_match(sprintf(static::REGEX_TWIG_REPLACEMENT_FUNCTION, $key), $text, $matches);
            if (!isset($matches[static::GROUP_REPLACEMENT_FUNCTION])) {
                continue;
            }

            $sanitizedTwigFunction = preg_replace(
                static::REGEX_ARGUMENTS,
                sprintf('(%s)', $matches[static::GROUP_ARGUMENTS] ?? ''),
                $twigFunction,
            );

            if (!$sanitizedTwigFunction) {
                continue;
            }

            $text = str_replace($matches[static::GROUP_REPLACEMENT_FUNCTION], $sanitizedTwigFunction, $text);
        }

        return $text;
    }
}
