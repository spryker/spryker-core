<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeXss;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilSanitizeXss\Dependency\External\UtilSanitizeToXssSanitizeInterface;
use Spryker\Service\UtilSanitizeXss\Escaper\CommentEscaper;
use Spryker\Service\UtilSanitizeXss\Escaper\EscaperInterface;
use Spryker\Service\UtilSanitizeXss\Escaper\TwigFunctionEscaper;
use Spryker\Service\UtilSanitizeXss\Sanitizer\Sanitizer;
use Spryker\Service\UtilSanitizeXss\Sanitizer\SanitizerInterface;

class UtilSanitizeXssServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilSanitizeXss\Sanitizer\SanitizerInterface
     */
    public function createSanitizer(): SanitizerInterface
    {
        return new Sanitizer(
            $this->getXssSanitizer(),
            $this->getEscapers(),
        );
    }

    /**
     * @return list<\Spryker\Service\UtilSanitizeXss\Escaper\EscaperInterface>
     */
    public function getEscapers(): array
    {
        return [
            $this->createTwigFunctionEscaper(),
            $this->createCommentEscaper(),
        ];
    }

    /**
     * @return \Spryker\Service\UtilSanitizeXss\Escaper\EscaperInterface
     */
    public function createTwigFunctionEscaper(): EscaperInterface
    {
        return new TwigFunctionEscaper();
    }

    /**
     * @return \Spryker\Service\UtilSanitizeXss\Escaper\EscaperInterface
     */
    public function createCommentEscaper(): EscaperInterface
    {
        return new CommentEscaper();
    }

    /**
     * @return \Spryker\Service\UtilSanitizeXss\Dependency\External\UtilSanitizeToXssSanitizeInterface
     */
    public function getXssSanitizer(): UtilSanitizeToXssSanitizeInterface
    {
        return $this->getProvidedDependency(UtilSanitizeXssDependencyProvider::XSS_SANITIZER);
    }
}
