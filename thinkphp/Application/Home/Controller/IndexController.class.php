<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：IndexController
 *      控制器作用：遍历首页信息
 *  @author wbx
 *  @date 2016-11-18
*/
class IndexController extends Controller {
    /**
     * 方法名 index()  遍历首页
     * @return [void]
    */
    public function index(){

        //全部贴吧推荐
        $bars = M('barinfo')->where(array('status'=>array('eq','1')))->order('attention desc')->limit(8)->select();

        $list = array();
        foreach($bars as $v){
            $v['count'] = M('note')->field(array("count(bar_id)"=>"count"))->where(array('bar_id'=>array('eq',$v['id'])))->select();

            $list[] = $v;
        }
        // dump($list);

        //热门帖子
        $note = M('note')->field("n.id,n.title,n.reply,n.user_id,u.name uname,n.content,n.isfine,b.id bar_id,b.hpic bhpic,b.name,n.createtime")->table('csw_note n,csw_barinfo b,csw_user u')->where('n.istop=1 and n.is_show=1 and n.bar_id=b.id and n.user_id=u.id')->select();
        // dump($note);

        //友情链接
        $links = M('link')->where(array('is_show'=>array('eq','1')))->select();
        // dump($links);

        //贴吧分类
        $typehead = M('type')->where(array('pid'=>array('eq',0)))->limit(7)->select();

        $types = M('type')->select();
        // dump($typehead);
        // dump($types);
        if($_SESSION['user']){
            $attenBars = attentionBars(2);
            $uid = $_SESSION['user']['id'];
            $mybars =M('bars')->field('b.name,ba.signtime,b.id,ba.integral,ba.grade,ba.id barsid')->table("csw_bars ba,csw_barinfo b")->where("ba.user_id=$uid and ba.bar_id=b.id and grade>=2")->select();
        }
        // dump($attenBars);
        $s = 0;
        $nowtime = date("ymd",time());
        foreach ($mybars as $v) {
            $d = date("ymd",$v['signtime']);
            // dump($d);
            // echo $nowtime;
            if($d !== $nowtime){
                $s = 1;
                break;
            }
        }

        $weather = $this->weather();
        // dump($weather);

        //全吧热门话题
        $hotnote = M('note')->limit(10)->order('reply desc')->select();
        // dump($hotnote);

        // $this->assign('suggest',$arr);
        $this->assign('bars',$list);
        $this->assign('notes',$note);
        $this->assign('typehead',$typehead);
        $this->assign('types',$types);
        $this->assign('links',$links);
        $this->assign('attenBars',$attenBars);
        $this->assign('signin',$s);
        $this->assign('weather',$weather);
        $this->assign('hotnote',$hotnote);

        $this->display();
    }

    /**
     * 方法名：isfine()  查找加精贴
     * @return [ajax]
    */
    public function isfine(){
        $note = M('note')->field("n.id,n.title,n.user_id,u.name uname,n.reply,n.content,n.isfine,b.id barid,b.hpic bhpic,b.name,n.createtime")->table('csw_note n,csw_barinfo b,csw_user u')->where('n.isfine=1 and n.is_show=1 and n.bar_id=b.id and n.user_id=u.id')->select();
        if(!$note){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn($note);
        }
    }

    /**
     * 方法名：ishot()  查找热门贴
     * @return [ajax]
    */
    public function ishot(){
        $note = M('note')->field("n.id,n.title,n.user_id,n.reply,u.name uname,n.content,n.isfine,b.id barid,b.hpic bhpic,b.name,n.createtime")->table('csw_note n,csw_barinfo b,csw_user u')->where('n.istop=1 and n.is_show=1 and n.bar_id=b.id and n.user_id=u.id')->select();
        if(!$note){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn($note);
        }
    }

    public function news()
    {
        $ch = curl_init();
        $url = 'http://apis.baidu.com/txapi/keji/keji?num=10&page=1&word=%E8%8B%B9%E6%9E%9C';
        $header = array(
            'apikey: b848d17197e4ead6adc43f2af62b4ac8',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);

        $res = json_decode($res);
        var_dump($res->newslist);
    }

    public function weather()
    {
        $ch = curl_init();
        $location = 'xiamen';
        $url = 'http://apis.baidu.com/thinkpage/weather_api/suggestion?location='.$location.'&language=zh-Hans&unit=c&start=0&days=3';
        $header = array(
            'apikey: b848d17197e4ead6adc43f2af62b4ac8',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);

        $res = json_decode($res);
        // dump($res);
        // exit;
        $data = $res->results;

        return($data[0]->daily);
        // $this->assign('data',$data[0]->daily);
        // $this->display('public/index');
    }
}