<?php
class IndexAction extends Action
{
    private $memcache;

    public function __construct(){
        parent::__construct();
        $this->memcache = memcache_init();
    }

    public function text($accountInfo,$wechatMessage,$params = array()){

        $content = trim($wechatMessage['Content']);
        $openid = $wechatMessage['FromUserName'];
        $key = '成语接龙';
        $exitkey = '退出接龙';
        $step = $this->memcache->get($openid.'_step');
        $model = M('chengyu');

        if($content == $key || $content == $exitkey){
            switch($content){
                case $key: //开始成语接龙
                    if(!$step){
                        $step = 1;
                        $this->memcache->set($openid.'_step',$step,0,300);
                        //出题
                        $rand = rand(1,13000);
                        $result = $model->where("id={$rand}")->find();
                        $msg = '第'.$step.'题：'.$result['chengyu'];
                        $this->memcache->set($openid.'_question',$result['chengyu']);
                        return $this->ReplyTxtMsg($msg);
                    } else {
                        return $this->ReplyTxtMsg('如果认输请输入"退出接龙"');
                    }
                    break;
                case $exitkey: //退出成语接龙
                    $this->memcache->delete($openid.'_question');
                    $this->memcache->delete($openid.'_step');
                    return $this->ReplyTxtMsg('请再接再厉！');
                    break;
            }

        } else {
            if(!empty($step)){
                //截取第一个字符
                $first = mb_substr($content,0,1,'utf-8');

                //截取上条消息最后一个字符
                $back = $this->memcache->get($openid.'_question');
                $length = mb_strlen($back,'utf-8') - 1;
                $last = mb_substr($back,$length,1,'utf-8');

                //匹配是否接上
                if($first == $last){

                    //检查该成语是否在成语库
                    $is = $model->where("chengyu = '{$content}'")->find();
                    if(!empty($is)){

                        //接龙
                        $first_length = mb_strlen($content,'utf-8') - 1;
                        $thelast = mb_substr($content,$first_length,1,'utf-8');
                        $result = $model->where("firstchar = '{$thelast}'")->order('RAND()')->find();

                        if($result){
                            //接龙成功
                            $step++;
                            $msg = '第'.$step.'题：'.$result['chengyu'];
                            $this->memcache->set($openid.'_step',$step,0,300);
                            $this->memcache->set($openid.'_question',$result['chengyu'],0,300);
                            return $this->ReplyTxtMsg($msg);
                        } else {
                            //接龙失败
                            $this->memcache->delete($openid.'_step');
                            $this->memcache->delete($openid.'_question');
                            return $this->ReplyTxtMsg('少年你真是饱读诗书，在下甘拜下风！');
                        }
                    } else {
                        return $this->ReplyTxtMsg('这不是一个成语');
                    }
                } else {
                    return $this->ReplyTxtMsg('你没有接上');
                }
            }
        }
    }

    public function ReplyTxtMsg($content){
        return array(
            'action' => 'reply',
            'data' => array(
                'type' => 'text',
                'message' => $content
            ),
            'exit'=>1
        );
    }
}

$server = new Yar_Server(new IndexAction());

$server->handle();