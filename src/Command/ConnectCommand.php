<?php
namespace Neusta\MageHost\Command;

use Neusta\MageHost\Services\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Created by PhpStorm.
 * User: tgostomski
 * Date: 27.01.17
 * Time: 18:10
 */
class ConnectCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('connect')
            // the short description shown while running "php bin/console list"
            ->setDescription('Get a list of availiable hosts');
        ;
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = new File();
        $hosts = $file->getHostsForQuestionhelper();
        $helper = new QuestionHelper();

        $question = new ChoiceQuestion(
            'Please select your favorite color (defaults to red)',
            $hosts,
            0
        );
        $question->setErrorMessage('Color %s is invalid.');

        $color = $helper->ask($input, $output, $question);
        $output->writeln('You have just selected: '.$color);
        return 0;
    }
}