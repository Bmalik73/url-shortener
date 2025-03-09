<?php

namespace App\Command;

use App\Repository\UrlRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cleanup-urls',
    description: 'Nettoie les URLs expirées et anciennes',
)]
class CleanupUrlsCommand extends Command
{
    private UrlRepository $urlRepository;

    public function __construct(UrlRepository $urlRepository)
    {
        parent::__construct();
        $this->urlRepository = $urlRepository;
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'days',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Nombre de jours après lesquels une URL non visitée est considérée comme ancienne',
                90
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $days = (int) $input->getOption('days');

        $io->title('Nettoyage des URLs');

        // Supprimer les URLs expirées
        $expiredCount = $this->urlRepository->deleteExpired();
        $io->success(sprintf('%d URLs expirées ont été supprimées.', $expiredCount));

        // Supprimer les URLs anciennes non visitées
        $olderThan = new \DateTimeImmutable("-{$days} days");
        $oldCount = $this->urlRepository->cleanupOldUrls($olderThan);
        $io->success(sprintf('%d URLs anciennes ont été supprimées (plus de %d jours sans visite).', $oldCount, $days));

        return Command::SUCCESS;
    }
}