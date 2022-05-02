<?php
namespace App\Command;

use App\Message\CrawlWebsiteMessage;
use App\Service\WebsiteCrawlerService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class AnalyticsCrawlerCommand extends Command
{
    protected $stats = ['unique_links', 'unique_images'];

    protected static $defaultName = 'analytics:crawler';

    private MessageBusInterface $messageBus;
    private WebsiteCrawlerService $httpCrawlerService;
    private LoggerInterface $logger;

    public function __construct(MessageBusInterface $messageBus, WebsiteCrawlerService $httpCrawlerService, LoggerInterface $logger, string $name = null)
    {
        parent::__construct($name);

        $this->messageBus = $messageBus;
        $this->httpCrawlerService = $httpCrawlerService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->addOption('url', 'u', InputOption::VALUE_REQUIRED, 'Url link to start crawling')
            ->addOption('max-pages', 'm', InputOption::VALUE_OPTIONAL, 'Number of pages to visit')
            ->addOption('stat', 's', InputOption::VALUE_OPTIONAL, 'Information to stat on page')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output) :int
    {
        $this->output = $output;

        $link = $input->getOption('url') ?? null;
        $maxPagesToCrawl = $input->getOption('max-pages') ?? -1;

        $crawlerStatJob = $this->httpCrawlerService->createNewStatJob();

        $this->httpCrawlerService
            ->setCrawlerJob($crawlerStatJob->getId())
            ->setInitialLink($link)
            ->setMaxPagesToVisit($maxPagesToCrawl)
        ;

        try {
            $this->httpCrawlerService->run();
            return Command::SUCCESS;
        } catch(\Throwable $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }
        return Command::FAILURE;
    }
}