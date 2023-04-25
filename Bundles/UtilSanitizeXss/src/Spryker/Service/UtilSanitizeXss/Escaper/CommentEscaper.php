<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeXss\Escaper;

class CommentEscaper implements EscaperInterface
{
    /**
     * @var string
     */
    protected const REGEX_COMMENT = '/(?<comment><!--(?<content>.*?)-->)/';

    /**
     * @var string
     */
    protected const REGEX_COMMENT_REPLACEMENT = '/(?<replacementComment>replacementCommentStart(?<content>.*?)replacementCommentEnd)/';

    /**
     * @var string
     */
    protected const PATTER_COMMENT = '<!--$2-->';

    /**
     * @var string
     */
    protected const PATTERN_COMMENT_REPLACEMENT = 'replacementCommentStart$2replacementCommentEnd';

    /**
     * - Searches comment tag using pattern.
     * - Replaces found comment tags with replacement tokens.
     *
     * @example `<!-- function() -->` becomes `replacementCommentStart function() replacementCommentEnd`
     *
     * @param string $text
     *
     * @return string
     */
    public function escape(string $text): string
    {
        return (string)preg_replace(
            static::REGEX_COMMENT,
            static::PATTERN_COMMENT_REPLACEMENT,
            $text,
        );
    }

    /**
     * - Searches for replacement tokens using pattern.
     * - Replaces found tokens with comment tag.
     *
     * @example `replacementCommentStart function() replacementCommentEnd` becomes `<!-- function() -->`
     *
     * @param string $text
     *
     * @return string
     */
    public function restore(string $text): string
    {
        return (string)preg_replace(
            static::REGEX_COMMENT_REPLACEMENT,
            static::PATTER_COMMENT,
            $text,
        );
    }
}
