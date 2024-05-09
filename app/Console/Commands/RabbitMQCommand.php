<?php

namespace App\Console\Commands;

use App\Services\AMQP\AMQPInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoInputDTO;
use Illuminate\Console\Command;

class RabbitMQCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumer RabbitMQ';

    public function __construct(
        private AMQPInterface $amqp,
        private ChangeEncodedPathVideo $usecase
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $clousure = function ($message) {
            $body = json_decode($message->body);
            var_dump($body);

            if (isset($body->Error) && $body->Error === '') {
                var_dump($body->video);

                $encodedPath = $body->video->encoded_video_folder.'/stream.mpd';
                $videoId = $body->video->resource_id;

                $input = new ChangeEncodedVideoInputDTO(
                    id: $videoId,
                    encodedPath: $encodedPath
                );

                $response = $this->usecase->exec($input);

                var_dump($response);

            }
        };

        $this->amqp->consumer(
            queue: config('microservices.queue_name'),
            exchange: config('microservices.micro_encoder_go.exchange_producer'),
            callback: $clousure
        );

        return Command::SUCCESS;
    }
}
