<?php

namespace Service\RabbitMQ;

/**
 * 消息生产者
 *
 * @package
 * @author panxin <panxin@aipai.com>
 */
class ProducerMQService extends BaseMQService
{
    /**
     * 路由key
     *
     * @var array
     */
    private $routes = ['hello','world'];

    /**
     * 初始化
     *
     * @return mixed
     * @author panxin <panxin@aipai.com>
     */
    public function __construct()
    {
        parent::__construct();
    }

    /** 
     * 只控制发送成功 不接受消费者是否收到
     * 
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     */
    public function run()
    {
        $sendEd  = true ;
        $channel = $this->channel(); //频道
        $ex      = $this->exchange();  //创建交换机对象
        $message = 'product message '.rand(1,99999); //消息内容
        //开始事务
        $channel->startTransaction();
            foreach ($this->routes as $route) {
                $sendEd = $ex->publish($message, $route) ;
                echo "Send Message:".$sendEd."\n";
            }
            if(!$sendEd) {
                $channel->rollbackTransaction();
            }
        $channel->commitTransaction();
        //提交事务
        $this->close();
        die ;
    }
}