<?php

namespace App\Services\AMQP;

use Closure;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class PhpAMQPService implements AMQPInterface
{
    protected $connection = null;
    protected $channel = null;

    public function producer(string $queue, array $payload, string $exchange): void
    {
        $this->connect();

		$this->channel->queue_declare($queue, false, true, false, false);
        $this->channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
        $this->channel->queue_bind($queue, $exchange);

        $message = new AMQPMessage(
            body: json_encode($payload),
            properties: [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'content_type' => 'text/plain',
            ]
        );
        
        $this->channel->basic_publish(
            msg: $message,
            exchange: $exchange
        );

        $this->closeChannel();
        $this->closeConnection();
    }

    public function producerFanout(array $payload, string $exchange): void
    {
        $this->connect();

        $this->channel->exchange_declare(
            exchange: $exchange,
            type: AMQPExchangeType::FANOUT,
            passive: false,
            durable: true,
            auto_delete: false
        );

        $message = new AMQPMessage(
            body: json_encode($payload),
            properties: [
                'content_type' => 'text/plain',
            ]
        );
        
        $this->channel->basic_publish(
            msg: $message,
            exchange: $exchange
        );

        $this->closeChannel();
        $this->closeConnection();
    }
    
    public function consumer(string $queue, string $exchange, Closure $callback): void
    {
        $this->connect();

        $this->channel->queue_declare(
            queue: $queue, 
            durable: true, 
            auto_delete: false
        );

        $this->channel->queue_bind(
            queue: $queue, 
            exchange: $exchange,
            routing_key: config('microservices.queue_name')
        );

        $this->channel->basic_consume(
            queue: $queue,
            callback: $callback
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

        $this->closeChannel();
        $this->closeConnection();

    }

    private function connect()
    {
        if ($this->connection) {
            return;
        }

        $configs = config('microservices.rabbitmq.hosts')[0];
        $this->connection = new AMQPStreamConnection(
            host: $configs['host'],
            port: $configs['port'],
            user: $configs['user'],
            password: $configs['password'],
            vhost: $configs['vhost']
        );

        $this->channel = $this->connection->channel();
    }

    private function closeChannel()
    { 
        $this->channel->close();
    }

    private function closeConnection()
    { 
        $this->connection->close();
    }
}