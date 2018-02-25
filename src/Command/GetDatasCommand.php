<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 04/02/2018
 * Time: 13:05
 */

namespace App\Command;

use App\Service\APIService;
use App\Service\DatasService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GetDatasCommand
 * @package App\Command
 */
class GetDatasCommand extends Command {

    /**
     * @var DatasService
     */
    private $datasService;

    /**
     * @var APIService
     */
    private $apiService;

    /**
     * GetDatasCommand constructor.
     * @param DatasService $datasService
     * @param APIService $APIService
     */
    public function __construct(DatasService $datasService, APIService $APIService) {
        $this->datasService = $datasService;
        $this->apiService = $APIService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:get-datas')

            // the short description shown while running "php bin/console list"
            ->setDescription('Get Poloniex Datas.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command get datas from poloniex...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $datas = $this->apiService->callPoloniexApi();
        if(!is_null($datas)) {
            $this->datasService->saveDatas($datas);
        }
    }
}