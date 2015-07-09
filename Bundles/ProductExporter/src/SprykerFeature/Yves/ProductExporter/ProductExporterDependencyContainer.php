<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\ProductExporter;

use Generated\Yves\Ide\FactoryAutoCompletion\ProductExporter;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;

/**
 * Class ProductExportDependencyContainer
 */
class ProductExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var ProductExporter
     */
    protected $factory;

    /**
     * @return ResourceCreator\ProductResourceCreator
     */
    public function createProductResourceCreator()
    {
        return $this->getFactory()->createResourceCreatorProductResourceCreator(
            $this->createFrontendProductBuilder(),
            $this->getLocator()
        );
    }

    /**
     * @return Builder\FrontendProductBuilder
     */
    protected function createFrontendProductBuilder()
    {
        return $this->getFactory()->createBuilderFrontendProductBuilder($this->getFactory());
    }

}
