<?php


namespace SlimQ\Messaging;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class QueuePublisher
{
    /**
     * @var AMQPChannel
     */
    private $channel;
    private $exchangeName;

    /**
     * QueuePublisher constructor.
     *
     * @param AMQPChannel $channel
     */
    public function __construct($exchangeName, AMQPChannel $channel)
    {
        $this->channel = $channel;
        $this->exchangeName = $exchangeName;
    }

    public function publish($class, array $arguments)
    {
        $message = json_encode(
            [
                'job' => $class,
                'args' => $arguments
            ]
        );

        $message = new AMQPMessage($message, array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $this->channel->basic_publish($message, $this->exchangeName);
    }
}