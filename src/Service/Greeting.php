<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class Greeting
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $message;

    public function __construct(LoggerInterface $logger ,string $message)
    {
        $this->logger = $logger;
        $this->message = $message;
    $this->message = $message;}

    public function greet(string $name): string
    {
        $this->logger->info("Gretted $name");
        return "{$this->message} $name";
    }
}