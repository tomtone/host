<?php
/**
 * Created by PhpStorm.
 * User: tgostomski
 * Date: 28.01.17
 * Time: 07:03
 */

namespace Neusta\MageHost\Command;

use Neusta\MageHost\Services\Hosts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    const SCOPE_LOCAL = 'local';
    const SCOPE_GLOBAL = 'global';
    const SCOPE_PROJECT = 'project';
    /**
     *
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('host:list')
            //
            // configure an argument
            ->addOption(
                'scope',
                null,
                InputOption::VALUE_OPTIONAL,
                'Use local or global scope',
                'global'
            )

            // the short description shown while running "php bin/console list"
            ->setDescription('list available hosts');
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
        $scope = $input->getOption('scope');
        if($scope != self::SCOPE_GLOBAL && $scope != self::SCOPE_LOCAL && $scope != self::SCOPE_PROJECT){
            throw new \InvalidArgumentException(printf('Scope "%s" not defined.', $scope));
        }
        $table = new Table($output);

        $hosts = new Hosts();
        $tableData = [];
        foreach ($hosts->getHosts($scope) as $host){
            $tableData[] = [
                $host['name'],
                $host['host'],
                $host['user'],
                'local',
            ];
        }

        $table
            ->setHeaders(array('Name', 'Host', 'User', 'Scope'))
            ->setRows($tableData)
        ;
        $table->render();
        return 0;
    }
}