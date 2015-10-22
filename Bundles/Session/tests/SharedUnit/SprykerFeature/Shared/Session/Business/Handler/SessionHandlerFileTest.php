<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SharedUnit\SprykerFeature\Shared\Session\Business\Handler;

use SprykerFeature\Shared\Session\Business\Handler\SessionHandlerFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @group SprykerFeature
 * @group Shared
 * @group Session
 * @group Business
 * @group SessionHandlerFile
 */
class SessionHandlerFileTest extends \PHPUnit_Framework_TestCase
{

    const LIFETIME = 20;

    const SESSION_NAME = 'sessionName';

    const SESSION_ID = 'sessionId';
    const SESSION_ID_2 = 'anotherSessionId';

    const SESSION_DATA = 'sessionData';

    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getFixtureDirectory());
    }

    /**
     * @return string
     */
    private function getFixtureDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures';
    }

    /**
     * @return string
     */
    protected function getSavePath()
    {
        return $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Sessions';
    }

    public function testCallOpenMustCreateDirectoryIfNotExists()
    {
        $this->assertFalse(is_dir($this->getSavePath()));

        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $sessionHandlerFile->open($this->getSavePath(), self::SESSION_NAME);

        $this->assertTrue(is_dir($this->getSavePath()));
    }

    public function testCallOpenMustReturnTrue()
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $result = $sessionHandlerFile->open($this->getSavePath(), self::SESSION_NAME);

        $this->assertTrue($result);
    }

    public function testCallCloseMustReturnTrue()
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $result = $sessionHandlerFile->close();

        $this->assertTrue($result);
    }

    public function testCallWriteMustReturnFalseIfNoDataPassed()
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $sessionHandlerFile->open($this->getSavePath(), self::SESSION_NAME);
        $result = $sessionHandlerFile->write(self::SESSION_ID, '');

        $this->assertFalse($result);
    }

    public function testCallWriteMustReturnTrueWhenDataCanBeWrittenToFile()
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $sessionHandlerFile->open($this->getSavePath(), self::SESSION_NAME);
        $result = $sessionHandlerFile->write(self::SESSION_ID, self::SESSION_DATA);

        $this->assertTrue($result);
    }

    public function testCallReadMustReturnContentOfSessionForGivenSessionId()
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $sessionHandlerFile->open($this->getSavePath(), self::SESSION_NAME);
        $sessionHandlerFile->write(self::SESSION_ID, self::SESSION_DATA);

        $result = $sessionHandlerFile->read(self::SESSION_ID);

        $this->assertSame(self::SESSION_DATA, $result);
    }

    public function testCallDestroyMustReturnFalseIfNoFileExistsForSessionId()
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $sessionHandlerFile->open($this->getSavePath(), self::SESSION_NAME);

        $result = $sessionHandlerFile->destroy(self::SESSION_ID);

        $this->assertFalse($result);
    }

    public function testCallDestroyMustReturnTrueIfFileExistsForSessionId()
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $sessionHandlerFile->open($this->getSavePath(), self::SESSION_NAME);
        $sessionHandlerFile->write(self::SESSION_ID, self::SESSION_DATA);

        $result = $sessionHandlerFile->destroy(self::SESSION_ID);

        $this->assertTrue($result);
    }

    public function testCallGcMustDeleteFilesWhichAreOlderThenMaxLifetime()
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), self::LIFETIME);
        $sessionHandlerFile->open($this->getSavePath(), self::SESSION_NAME);
        $sessionHandlerFile->write(self::SESSION_ID, self::SESSION_DATA);
        $this->makeFileOlderThanItIs();
        $sessionHandlerFile->write(self::SESSION_ID_2, self::SESSION_DATA);
        $this->makeFileNewerThanItIs();

        $finder = new Finder();
        $finder->in($this->getSavePath());

        $this->assertCount(2, $finder);

        $sessionHandlerFile->gc(1);
        $this->assertCount(1, $finder);

        unlink($this->getSavePath() . '/session:' . self::SESSION_ID_2);
        rmdir($this->getSavePath());
    }

    /**
     * @return void
     */
    protected function makeFileOlderThanItIs()
    {
        touch($this->getSavePath() . DIRECTORY_SEPARATOR . 'session:' . self::SESSION_ID, time() - 200);
    }

    /**
     * @return void
     */
    protected function makeFileNewerThanItIs()
    {
        touch($this->getSavePath() . DIRECTORY_SEPARATOR . 'session:' . self::SESSION_ID_2, time() + 200);
    }
}
