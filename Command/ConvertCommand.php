<?php


namespace Kif\DoctrineToTypescriptBundle\Command;


use Kif\DoctrineToTypescriptBundle\Service\EntityIterator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends ContainerAwareCommand
{


    /**
     * configure the main command line
     */
    protected function configure()
    {
        $this
            ->setName('kif:doctrine:typescript:generate')
            ->setDescription('Convert doctrine entities into Typescript classes');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $output->writeln('<info>Generating Typescript....</info>');
        $directoryIterator = new EntityIterator($em);
        $directoryIterator->directoryIterator();
    }


}
