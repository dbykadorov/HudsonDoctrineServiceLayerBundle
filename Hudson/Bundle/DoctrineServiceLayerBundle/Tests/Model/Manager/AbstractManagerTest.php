<?php

namespace Hudson\Bundle\DoctrineServiceLayerBundle\Tests\Model\Manager;

/**
 * Class AbstractManagerTest
 *
 * @author Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 */
class AbstractManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * AbstractManager::checkManagedClass
     * 
     * @expectedException \Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\Exception\ModelManagerException
     * @expectedExceptionMessage Empty managed class name
     */
    public function testCheckManagedClassEmptyName()
    {
        $this->getMockForAbstractClass(
            'Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\AbstractManager',
            array(null, null)
        );
    }

    /**
     * AbstractManager::checkManagedClass
     *
     * @expectedException \Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\Exception\ModelManagerException
     * @expectedExceptionMessage Managed class name must be a string with fully qulified class name, "integer" given
     */
    public function testCheckManagedClassNotString()
    {
        $this->getMockForAbstractClass(
            'Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\AbstractManager',
            array(100, null)
        );
    }

    /**
     * AbstractManager::checkManagedClass
     *
     * @expectedException \Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\Exception\ModelManagerException
     * @expectedExceptionMessage Managed class "Foo\Bar\Entity" does not exists or can't be autoloaded
     */
    public function testCheckManagedClassWhenClassNotExists()
    {
        $this->getMockForAbstractClass(
            'Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\AbstractManager',
            array("Foo\\Bar\\Entity", null)
        );
    }

    /**
     * AbstractManager::isSupported
     *
     */
    public function testIsSupported()
    {
        $stub = $this->getMockForAbstractClass(
            'Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\AbstractManager',
            array(),
            "",
            $callOriginalConstructor = false
        );

        $stub->setManagedClass(get_class($this));

        $this->assertTrue($stub->isSupported($this));
    }

    /**
     * AbstractManager::isSupported
     *
     * @expectedException \Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\Exception\ModelManagerException
     * @expectedExceptionMessage Expected object, "string" given
     */
    public function testIsSupportedWrongParameterType()
    {
        $stub = $this->getMockForAbstractClass(
            'Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\AbstractManager',
            array(),
            "",
            $callOriginalConstructor = false
        );

        $stub->setManagedClass(get_class($this));

        $stub->isSupported("foobar");
    }

    // @codingStandardsIgnoreStart
    /**
     * AbstractManager::isSupported
     *
     * @expectedException \Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\Exception\ModelManagerException
     * @expectedExceptionMessage Model manager for "Hudson\Bundle\DoctrineServiceLayerBundle\Tests\Model\Manager\AbstractManagerTest" does not supports class "stdClass"
     */
    // @codingStandardsIgnoreEnd
    public function testIsSupportedWrongClass()
    {
        $stub = $this->getMockForAbstractClass(
            'Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\AbstractManager',
            array(),
            "",
            $callOriginalConstructor = false
        );

        $stub->setManagedClass(get_class($this));

        $stub->isSupported(new \StdClass());
    }
}
