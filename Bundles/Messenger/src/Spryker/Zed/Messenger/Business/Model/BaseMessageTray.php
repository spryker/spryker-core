<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger\Business\Model;

use Spryker\Zed\Messenger\Dependency\Facade\MessengerToGlossaryInterface;

class BaseMessageTray
{

    /**
     * @var MessengerToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Messenger\Dependency\Facade\MessengerToGlossaryInterface $glossaryFacade
     */
    public function __construct(MessengerToGlossaryInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    protected function translate($keyName, array $data = [])
    {
        $translation = $keyName;
        if ($this->glossaryFacade->hasKey($keyName)) {
            $translation = $this->glossaryFacade->translate($keyName, $data);
        }

        return $translation;
    }

}
