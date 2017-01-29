<?php
namespace Neusta\Hosts\Command;

use Neusta\Hosts\Services\HostService;
use Neusta\Hosts\Services\Provider\Cli;
use Neusta\Hosts\Services\Validator\Scope;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;


class ConnectCommand extends AbstractCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('connect')
            ->addOption(
                'scope',
                null,
                InputOption::VALUE_OPTIONAL,
                'Use local or global scope',
                null
            )
            ->setDescription('Get a list of availiable hosts');;
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
        $scope = $input->getOption('scope');
        Scope::validateScope($scope);

        $hostService = $this->_hostService;
        $hostService->setScope($scope);

        $hosts = $hostService->getHostsForQuestionhelper();
        $hosts[] = 'exit';
        $helper = new QuestionHelper();

        $question = new ChoiceQuestion(
            'Please select a host:',
            $hosts,
            0
        );
        $question->setErrorMessage('Host #%s is invalid.');

        $host = $helper->ask($input, $output, $question);

        if ($host == 'exit') {
            $output->writeln('exiting.');
            $output->writeln('have a nice day :-)');
            return 0;
        }

        $output->writeln('You have selected: ' . $host);
        $output->writeln("establishing connection...");
        $string = $hostService->getConnectionStringByName($host);
        if ($this->getApplication()->getEnvironment() == 'prod') {
            Cli::passthruSsh($string);
        }
        return 0;
    }
}