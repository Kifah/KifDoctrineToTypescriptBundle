<?php


namespace Kif\DoctrineToTypescriptBundle\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Kif\DoctrineToTypescriptBundle\Command\ConvertCommand;

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
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

    }


    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments.
     */
    public function executeMissingArguments()
    {
        $application = new Application();
        $application->add(new ConvertCommand($this->em));

        $command = $application->find('kif:doctrine:typescript:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

    }

    /**
     * @test
     * @expectedException Symfony\Component\Filesystem\Exception\FileNotFoundException
     * @expectedExceptionMessage The destination folder does not exist.
     */
    public function destinationFolderDoesNotExist()
    {
        $application = new Application();
        $application->add(new ConvertCommand($this->em));

        $command = $application->find('kif:doctrine:typescript:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), 'destination_folder' => 'my_test_folder/')
        );
    }


}
