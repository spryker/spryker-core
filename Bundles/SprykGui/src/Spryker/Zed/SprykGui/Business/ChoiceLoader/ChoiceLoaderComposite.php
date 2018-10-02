<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\ChoiceLoader;

use Exception;
use Generated\Shared\Transfer\ModuleTransfer;

class ChoiceLoaderComposite implements ChoiceLoaderCompositeInterface
{
    /**
     * @var \Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface[]
     */
    protected $choiceLoader;

    /**
     * @param \Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface[] $choiceLoader
     */
    public function __construct(array $choiceLoader)
    {
        $this->choiceLoader = $choiceLoader;
    }

    /**
     * @param string $choiceLoaderName
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @throws \Exception
     *
     * @return array
     */
    public function loadChoices(string $choiceLoaderName, ModuleTransfer $moduleTransfer): array
    {
        foreach ($this->choiceLoader as $choiceLoader) {
            if ($this->getChoiceLoaderShortName($choiceLoader) === $choiceLoaderName) {
                return $choiceLoader->loadChoices($moduleTransfer);
            }
        }

        throw new Exception(sprintf(
            'Could not find a matching ChoiceLoader by name "%s". Available ChoiceLoader: "%s"',
            $choiceLoaderName,
            implode(', ', $this->getChoiceLoaderNames())
        ));
    }

    /**
     * @param \Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface $choiceLoader
     *
     * @return string
     */
    protected function getChoiceLoaderShortName(ChoiceLoaderInterface $choiceLoader): string
    {
        $choiceLoaderClassNameFragments = explode('\\', get_class($choiceLoader));

        return array_pop($choiceLoaderClassNameFragments);
    }

    /**
     * @return array
     */
    protected function getChoiceLoaderNames(): array
    {
        $choiceLoaderNames = [];
        foreach ($this->choiceLoader as $choiceLoader) {
            $choiceLoaderNames[] = $this->getChoiceLoaderShortName($choiceLoader);
        }

        return $choiceLoaderNames;
    }
}
