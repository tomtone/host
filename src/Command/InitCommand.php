<?php
/**
 * *
 *  * This file is part of the teamneusta/codeception-docker-chrome package.
 *  *
 *  * Copyright (c) 2017 neusta GmbH | Ein team neusta Unternehmen
 *  *
 *  * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *  *
 *  * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 *  
 */

namespace Neusta\Hosts\Command;

use Neusta\Hosts\Exception\HostAlreadySet;
use Neusta\Hosts\Exception\HostAlreadySetException;
use Neusta\Hosts\Services\InitService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class InitCommand
 * not implemented yet.
 *
 * @codeCoverageIgnore
 *
 * @package Neusta\Hosts\Command
 */
class InitCommand extends Command
{
    /**
     * @var InitService
     */
    protected $_initService;

    /**
     * Constructor.
     * @codeCoverageIgnore
     *
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     *
     * @param InitService $initService
     */
    public function __construct($name = null, InitService $initService = null)
    {
        parent::__construct($name);
        if (is_null($initService)) {
            $initService = new InitService();
        }
        $this->_initService = $initService;
    }

    /**
     * Announce name and description so command could be called.
     *
     * @codeCoverageIgnore
     */
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('init hosts');
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
        $output->writeln("Still work in Progress!");
        return 0;
        $output->writeln("Checking for local configuration file..");
        if (!$this->_initService->localConfigurationExist()) {
            $output->writeln("Creating empty local configuration file.");
            $this->_initService->createLocalConfiguration();
        }
        $helper = new QuestionHelper();

        $question = new Question('Please enter Path for globally used hosts: ');
        $globalHostsUrl = $helper->ask($input, $output, $question);

        /** @var \Neusta\Hosts\Console\Application $application */
        $application = $this->getApplication();
        if (
            $application instanceof \Neusta\Hosts\Console\Application &&
            $application->getEnvironment() == 'prod'
        ) {
            try {
                $this->_initService->addGlobalHostUrl($globalHostsUrl);
            } catch (HostAlreadySetException $e) {
                $question = new ConfirmationQuestion(
                    sprintf("Value already set to \"%s\".\nYou want to override the value? (default is n) ", $e->getValue()),
                    false,
                    '/^(y|j)/i'
                );
                if ($helper->ask($input, $output, $question)) {
                    $output->writeln("Overwriting value to \"$globalHostsUrl\"");
                    $this->_initService->addGlobalHostUrl($globalHostsUrl, true);
                } else {
                    $output->writeln("Nothing to do.");
                }
            }
        }
        $output->writeln('Init done...');
        return 0;
    }
}