<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Spryker\Zed\Transfer\TransferConfig;

abstract class AbstractTransferDefinitionLoader implements LoaderInterface
{
    /**
     * @var string
     */
    public const KEY_BUNDLE = 'bundle';

    /**
     * @var string
     */
    public const KEY_CONTAINING_BUNDLE = 'containing bundle';

    /**
     * @var array
     */
    protected $transferDefinitions = [];

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface $finder
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizerInterface $definitionNormalizer
     * @param \Spryker\Zed\Transfer\TransferConfig $transferConfig
     */
    public function __construct(
        protected FinderInterface $finder,
        protected DefinitionNormalizerInterface $definitionNormalizer,
        protected TransferConfig $transferConfig
    ) {
    }

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        $this->loadDefinitions();
        $this->transferDefinitions = $this->definitionNormalizer->normalizeDefinitions(
            $this->transferDefinitions,
        );

        return $this->transferDefinitions;
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    protected function getContainingBundleFromPathName(string $filePath): string
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $filePath);
        $sharedDirectoryPosition = array_search('Shared', array_values($pathParts));

        $containingBundle = $pathParts[$sharedDirectoryPosition + 1];

        return $containingBundle;
    }

    /**
     * @return void
     */
    abstract protected function loadDefinitions(): void;
}
