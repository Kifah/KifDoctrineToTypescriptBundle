<?php


namespace Kif\DoctrineToTypescriptBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends Command
{


    /**
     * configure the main command line
     */
    protected function configure()
    {
        $this
            ->setName('kif:doctrine:typescript:generate')
            ->setDescription('Convert doctrine entities into Typescript classes')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Where are the source entities?'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');
        if (!$source) {
            $text = 'You have to type the source folder of your entities';
        } else {
            $output->writeln('<info>Generating Typescript....</info>');
            $text = $source;
        }


        $output->writeln($text);
    }
}
