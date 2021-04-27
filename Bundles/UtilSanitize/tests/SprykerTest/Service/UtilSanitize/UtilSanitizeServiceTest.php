<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilSanitize;

use Codeception\Test\Unit;
use Spryker\Service\UtilSanitize\UtilSanitizeDependencyProvider;
use Spryker\Service\UtilSanitize\UtilSanitizeService;
use Spryker\Service\UtilSanitizeExtension\Dependency\Plugin\StringSanitizerPluginInterface;
use SprykerTest\Service\Testify\Helper\DependencyProviderHelperTrait;
use SprykerTest\Service\Testify\Helper\ServiceHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilSanitize
 * @group UtilSanitizeServiceTest
 * Add your own group annotations below this line
 */
class UtilSanitizeServiceTest extends Unit
{
    use ServiceHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @return void
     */
    public function testSanitizeHtmlShouldEscapeGivenHtmlTags(): void
    {
        $utilSanitizeService = $this->createUtilSanitizeService();

        $escapedHtml = $utilSanitizeService->escapeHtml('<b></b>');

        $this->assertSame('&lt;b&gt;&lt;/b&gt;', $escapedHtml);
    }

    /**
     * @return void
     */
    public function testSanitizeStringReturnsSanitizedString(): void
    {
        // Arrange
        $this->getDependencyProviderHelper()->setDependency(
            UtilSanitizeDependencyProvider::PLUGINS_STRING_SANITIZER,
            function () {
                $sanitizer = new class implements StringSanitizerPluginInterface {
                    /**
                     * @param string $value
                     * @param string $replacement
                     *
                     * @return string
                     */
                    public function sanitize(string $value, string $replacement): string
                    {
                        return str_replace('data to sanitize', $replacement, $value);
                    }
                };

                return [$sanitizer];
            }
        );
        $utilSanitizeService = $this->createUtilSanitizeService();

        // Act
        $sanitizedString = $utilSanitizeService->sanitizeString('My string with "data to sanitize".');

        // Assert
        $this->assertSame('My string with "***".', $sanitizedString);
    }

    /**
     * @return \Spryker\Service\UtilSanitize\UtilSanitizeService
     */
    protected function createUtilSanitizeService(): UtilSanitizeService
    {
        /** @var \Spryker\Service\UtilSanitize\UtilSanitizeService $utilSanitizeService */
        $utilSanitizeService = $this->getServiceHelper()->getService();

        return $utilSanitizeService;
    }
}
