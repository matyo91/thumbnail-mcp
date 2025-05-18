<?php

namespace App\Command;

use App\Tool\ThumbnailTool;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-thumbnail',
    description: 'Test the thumbnail generation tool',
)]
class TestThumbnailCommand extends Command
{
    public function __construct(
        private ThumbnailTool $thumbnailTool
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Name to use in the thumbnail', 'John Doe');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        $io->note(sprintf('Testing thumbnail generation for name: %s', $name));

        try {
            $result = ($this->thumbnailTool)($name);
            $io->success($result);
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error('Error generating thumbnail: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 