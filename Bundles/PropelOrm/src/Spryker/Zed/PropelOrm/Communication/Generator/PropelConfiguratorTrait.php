<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Communication\Generator;

use Propel\Generator\Config\GeneratorConfig;
use Propel\Generator\Config\GeneratorConfigInterface;
use Symfony\Component\Console\Input\InputInterface;

trait PropelConfiguratorTrait
{
    /**
     * @var array
     */
    protected $propelConfig = [];

    /**
     * @param array $propelConfig
     *
     * @return static
     */
    public function setPropelConfig(array $propelConfig)
    {
        $this->propelConfig = $propelConfig;

        return $this;
    }

    /**
     * @param array|null $properties
     * @param \Symfony\Component\Console\Input\InputInterface|null $input
     *
     * @return \Propel\Generator\Config\GeneratorConfig
     */
    protected function getGeneratorConfig(?array $properties = null, ?InputInterface $input = null): GeneratorConfigInterface
    {
        return new GeneratorConfig(null, [
            'propel' => $this->propelConfig,
        ]);
    }
}
