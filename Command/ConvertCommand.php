<?php


namespace Kif\DoctrineToTypescriptBundle\Command;


use Kif\DoctrineToTypescriptBundle\Service\EntityIterator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ConvertCommand extends ContainerAwareCommand
{


    /**
     * configure the main command line
     */
    protected function configure()
    {
        $this
            ->setName('kif:doctrine:typescript:generate')
            ->setDescription('Convert doctrine entities into Typescript classes')
            ->addArgument('destination_folder', InputArgument::REQUIRED, 'In which folder to generate the .ts files?')
            ->addOption(
                'exposed-only',
                null,
                InputOption::VALUE_NONE,
                'If set, only exposed entites/variables will be generated'
            );

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $destinationFolder =$input->getArgument('destination_folder');
        $exposedOnly = false;
        $generateSingleFile  = false;
        if ($input->getOption('exposed-only')) {
            $output->writeln('<info>Generating only exposed entities....</info>');
            if (!$this->getContainer()->has('jms_serializer')) {
                throw new ServiceNotFoundException(
                    'install the jms serializer bundle to use the --exposed-only option'
                );
            }
            $exposedOnly = true;
        }

        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $output->writeln('<info>Generating Typescript....</info>');
        $entityIterator = new EntityIterator($em, $destinationFolder,$exposedOnly,$generateSingleFile);
        $entityIterator->entityBundlesIterator();


    }


}
