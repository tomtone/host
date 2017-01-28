<?php
/**
 * Created by PhpStorm.
 * User: tgostomski
 * Date: 27.01.17
 * Time: 18:14
 */

namespace Neusta\MageHost\Command;

use Neusta\MageHost\Services\HostService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('host:add')
            ->setDescription('interactively add new hosts');;
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input An InputInterface instance
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
        $helper = new QuestionHelper();

        $question = new Question('Please enter name: ');
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Please enter host: ');
        $hostname = $helper->ask($input, $output, $question);

        $question = new Question('Please enter username: ');
        $user = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion(
            'Please select a host:',
            [
                'local',
                'project'
            ],
            0
        );
        $question->setErrorMessage('Host #%s is invalid.');
        $scope = $helper->ask($input, $output, $question);
        if ($this->getApplication()->getEnvironment() == 'prod') {
            $hostService = new HostService();
            $hostService->setScope($scope);
            $hostService->update($name, $hostname, $user);
        }
        $output->writeln('Added Entry: ' .$user . '@' . $hostname . ' for ' . $scope . ' scope.');
        return 0;
    }
}