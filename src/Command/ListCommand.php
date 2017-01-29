<?php
namespace Neusta\Hosts\Command;

use Neusta\Hosts\Services\HostService;
use Neusta\Hosts\Services\Validator\Scope;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends AbstractCommand
{
    /**
     * configure for host:list command.
     */
    protected function configure()
    {
        $this
            ->setName('host:list')
            ->addOption(
                'scope',
                null,
                InputOption::VALUE_OPTIONAL,
                'Use local or global scope',
                null
            )
            ->setDescription('list available hosts');;
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
        $table = new Table($output);
        $tableData = [];
        $hosts = $this->_hostService->getHosts($scope);

        if (count($hosts) > 0) {
            foreach ($hosts as $host) {
                $tableData[] = [
                    $host['name'],
                    $host['host'],
                    $host['user'],
                    $host['scope'],
                ];
            }
        } else {
            $tableData[] = array(new TableCell('No entries found.', array('colspan' => 4)));
        }

        $table
            ->setHeaders(array('Name', 'Host', 'User', 'Scope'))
            ->setRows($tableData);
        $table->render();
        return 0;
    }
}