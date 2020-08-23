<?php

namespace Service\RabbitMQ;

/** Member
 *      AMQPChannel
 *      AMQPConnection
 *      AMQPEnvelope
 *      AMQPExchange
 *      AMQPQueue
 * Class BaseMQ
 * @package App\Service\RabbitMQ
 * @author panxin <panxin@aipai.com>
 */
abstract class BaseMQService
{
    /** 
     * MQ Channel
     * @var \AMQPChannel
     */
    protected $AMQPChannel;

    /** 
     * MQ connect
     * @var \$AMQPConnection
     */
    protected $AMQPConnection;

    /** 
     * MQ Envelope
     * @var \AMQPEnvelope
     */
    protected $AMQPEnvelope;

    /** 
     * MQ Exchange
     * @var \AMQPExchange
     */
    public $AMQPExchange;

    /** 
     * MQ Queue
     * @var \AMQPQueue
     */
    protected $AMQPQueue;

    /** 
     * exchange
     * @var
     */
    protected $exchange;

    /**
     * 连接相关的配置
     * 
     * @array $config
     */
    private $config;


    /**
     * 初始化MQ配置
     *
     * @return mixed
     * @author panxin <panxin@aipai.com>
     */
    public function __construct()
    {
        $config = config('rabbitmq');
        if(!$config){
            throw new \AMQPConnectionException('config error!');
        }
        $this->config         = $config['host'];
        $this->exchange       = $config['exchange'];
        $this->AMQPConnection = new \AMQPConnection($this->config);
        if (!$this->AMQPConnection->connect()){
            throw new \AMQPConnectionException("Cannot connect to the broker!\n");
        }
    }

    /**
     * close link
     *
     * @return void
     * @author panxin <panxin@aipai.com>
     */
    public function close()
    {
        $this->AMQPConnection->disconnect();
    }

    /** 
     * Channel
     * @return \AMQPChannel
     * @throws \AMQPConnectionException
     * 
     * @author panxin <panxin@aipai.com>
     */
    public function channel()
    {
        if(!$this->AMQPChannel) {
            $this->AMQPChannel =  new \AMQPChannel($this->AMQPConnection);
        }
        return $this->AMQPChannel;
    }

    /** 
     * Exchange
     * 
     * @return \AMQPExchange
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     *
     * @author panxin <panxin@aipai.com>
     */
    public function exchange()
    {
        if(!$this->AMQPExchange) {
            $this->AMQPExchange = new \AMQPExchange($this->channel());
            $this->AMQPExchange->setName($this->exchange);
        }
        return $this->AMQPExchange;
    }

    /** 
     * queue
     * 
     * @return \AMQPQueue
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     * 
     * @author panxin <panxin@aipai.com>
     */
    public function queue()
    {
        if(!$this->AMQPQueue) {
            $this->AMQPQueue = new \AMQPQueue($this->channel());
        }
        return $this->AMQPQueue ;
    }

    /** 
     * Envelope
     * @return \AMQPEnvelope
     * 
     * @author panxin <panxin@aipai.com>
     */
    public function envelope()
    {
        if(!$this->AMQPEnvelope) {
            $this->AMQPEnvelope = new \AMQPEnvelope();
        }
        return $this->AMQPEnvelope;
    }
}
