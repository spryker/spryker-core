<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateReader implements ConfigurableBundleTemplateReaderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface
     */
    protected $configurableBundleTemplateNameGenerator;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface $configurableBundleTemplateNameGenerator
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleTemplateNameGeneratorInterface $configurableBundleTemplateNameGenerator
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->configurableBundleTemplateNameGenerator = $configurableBundleTemplateNameGenerator;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateTransfer = $this->configurableBundleRepository
            ->findConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateTransfer) {
            return null;
        }

        return $this->configurableBundleTemplateNameGenerator->generateConfigurableBundleTemplateTranslationKey($configurableBundleTemplateTransfer);
    }
}
