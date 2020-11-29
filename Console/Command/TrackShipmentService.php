<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\P2p\Console\Command;

use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TrackShipmentService
 * @package SoftCommerce\P2p\Console\Command
 */
class TrackShipmentService extends Command
{
    const COMMAND_NAME = 'softcommerce_p2p:track_shipment';
    const ID_FILTER = 'id';

    /**
     * @var State
     */
    private $appState;

    /**
     * TrackShipmentService constructor.
     * @param State $appState
     * @param string|null $name
     */
    public function __construct(
        State $appState,
        string $name = null
    ) {
        $this->appState = $appState;
        parent::__construct($name);
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Track shipping number.')
            ->setDefinition([
                new InputOption(
                    self::ID_FILTER,
                    '-i',
                    InputOption::VALUE_REQUIRED,
                    'ID Filter'
                )
            ]);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @todo track shipment command */
        return;
    }
}
