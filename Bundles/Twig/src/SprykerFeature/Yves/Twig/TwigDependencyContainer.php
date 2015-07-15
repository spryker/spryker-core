<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Twig;

use Generated\Yves\Ide\FactoryAutoCompletion\Twig;
use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Yves\Twig\Model\YvesExtension;

/**
 * @method Twig getFactory()
 */
class TwigDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var TwigSettings
     */
    private $settings;

    /**
     * @param Application $application
     *
     * @return YvesExtension
     */
    public function createYvesTwigExtension(Application $application)
    {
        return $this->getFactory()->createModelYvesExtension($application, $this->getSettings());
    }

    /**
     * @retrun TwigSettings
     */
    protected function getSettings()
    {
        if (!isset($this->settings)) {
            $this->settings = $this->getFactory()->createTwigSettings($this->getLocator());
        }

        return $this->settings;
    }

}
