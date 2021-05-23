<?php

declare(strict_types=1);

namespace DatingLibre\CcBillBundle\Command;

use DatingLibre\CcBillBundle\Service\CcBillClientService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ViewSubscriptionStatus extends Command
{
    private const SUBSCRIPTION_ID = 'subscriptionId';
    protected static $defaultName = 'app:ccbill:status';
    private CcBillClientService $ccBillClientService;

    public function __construct(CcBillClientService $ccBillClientService)
    {
        parent::__construct();
        $this->ccBillClientService = $ccBillClientService;
    }

    protected function configure()
    {
        $this->setDescription('View ccbill subscription status');

        $this->addArgument(self::SUBSCRIPTION_ID, InputArgument::REQUIRED, self::SUBSCRIPTION_ID);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln($this->ccBillClientService->viewSubscriptionStatus($input->getArgument(self::SUBSCRIPTION_ID)));

        return 0;
    }
}