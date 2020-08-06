<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Remover;

use InvalidArgumentException;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Spryker\Zed\Development\DevelopmentConfig;

class TargetDirectoryResolver
{
    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    private $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $application
     *
     * @return string
     */
    public function resolveTargetDirectory(string $application): string
    {
        $options = $this->getOptions($application);

        $baseDirectory = rtrim(
            $options[IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY],
            DIRECTORY_SEPARATOR
        );

        $applicationPathFragment = trim(
            str_replace(
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
                $application,
                $options[IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN]
            ),
            DIRECTORY_SEPARATOR
        );

        return "{$baseDirectory}/{$applicationPathFragment}/";
    }

    /**
     * @param string $application
     *
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    private function getOptions(string $application): array
    {
        switch ($application) {
            case IdeAutoCompletionOptionConstants::YVES:
                return $this->config->getYvesIdeAutoCompletionOptions();
            case IdeAutoCompletionOptionConstants::ZED:
                return $this->config->getZedIdeAutoCompletionOptions();
            case IdeAutoCompletionOptionConstants::CLIENT:
                return $this->config->getClientIdeAutoCompletionOptions();
            case IdeAutoCompletionOptionConstants::GLUE:
                return $this->config->getGlueIdeAutoCompletionOptions();
            case IdeAutoCompletionOptionConstants::SERVICE:
                return $this->config->getServiceIdeAutoCompletionOptions();
            default:
                throw new InvalidArgumentException("Unable to resolve ide autocompletion directory for application ${application}");
        }
    }
}
