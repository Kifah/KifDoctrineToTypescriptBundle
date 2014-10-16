<?php


namespace Kif\DoctrineToTypescriptBundle\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ConvertCommandTest extends KernelTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;


    /**
     * root directory
     *
     * @type  vfsStreamDirectory
     */
    protected $root;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

    }


    /**
     * @test
     * @expectedException \Exception
     * @expectedExceptionMessage No Doctrine Entities on your system.
     */
    public function noDoctrineEntities()
    {
        $application = new Application();
        $application->add(new ConvertCommand([]));

        $command = $application->find('kif:doctrine:typescript:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function executeMissingArguments()
    {
        $application = new Application();
        $application->add(new ConvertCommand([1]));

        $command = $application->find('kif:doctrine:typescript:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

    }


    /**
     * @test
     * @expectedException Symfony\Component\Filesystem\Exception\FileNotFoundException
     * @expectedExceptionMessage Destination folder does not exist.
     */
    public function destinationFolderDoesNotExist()
    {
        $application = new Application();
        $application->add(new ConvertCommand([1]));
        $command = $application->find('kif:doctrine:typescript:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), 'destination_folder' => 'my_test_folder/')
        );
    }

    /**
     * @test
     * @expectedException Symfony\Component\Filesystem\Exception\IOException
     * @expectedExceptionMessage Destination Folder is not writable.
     */
    public function destinationFolderNotWritable()
    {
        $application = new Application();
        $application->add(new ConvertCommand([1]));
        $command = $application->find('kif:doctrine:typescript:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), 'destination_folder' => '/home/')
        );
    }


}
