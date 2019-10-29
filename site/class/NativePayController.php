<?php
/**
 *
 * Controller for Product
 *
 * @desc
 * @author
 * $date
 */
class NativePayController extends Fuse_Controller
{
    private $url = '/product';
    protected $language = array(
        'error_illegal' => '非法提交',
        'error_tel_empty' => '手机号码为空',
        'error_telephone' => '手机号码格式错误',
        'error_password_empty' => '密码为空',
        'error_password' => '密码使用了非法字符',
        'error_login' => '手机号或密码错误',
        'error_smscode_empty' => '短信验证码为空',
        'error_smscode' => '短信验证码出错',
        'config_name' => '易酷',
        'title' => '购买'
    );

    /**
     * Constructor
     *
     * @params	array	Controller configuration array
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        if (!Fuse_Customer::getInstanceCustomer()->isLogged()) {
            Fuse_Response::redirect('/');
        }

        $this->registerTask('index', 'index');
        $this->registerTask('editData', 'editData');

        $this->userId   = Fuse_Session::getInstance()->data['customer_id'];
        //$this->roleId   = Fuse_Session::getInstance()->role_id;
        $this->username = Fuse_Session::getInstance()->data['username'];

        $this->model = $this->createModel('Model_Checkout', dirname( __FILE__ ));

        $this->session = Fuse_Session::getInstance();
    }

    /**
     * 下单页面
     */
    public function index()
    {
        $data = array();

        //$data['itemList'] = $this->model->getList();
        //$data['serverList'] = $this->model->getServerList();

        // 订单信息
        $order = $this->model->getOrderById($this->session->data['orderId']);
//ECHO '<PRE>';PRINT_r($order);exit;
        $product = $this->session->data['product'];
        $data['companyName'] = $this->session->data['companyName'];
        //$data['version'] = $this->session->data['version'] = 'team';
        $data['num'] = $this->session->data['num'];
        $data['years'] = $this->session->data['years'];
        $data['perprice'] = $this->session->data['perprice'];
        $data['total'] = $this->session->data['total'];

        $data['body'] = "商品描述";
        $data['attach'] = "附加数据"; // 可作为自定义参数使用
        $data['out_trade_no'] = $order['order_no']; //商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|* 且在同一个商户号下唯一。
        $data['total_fee'] = (int)$order['total_price'] * 100; // 订单总金额，单位为分
        $data['time_start'] = date("YmdHis");
        $data['expire_time'] = '600'; // 订单失效时间
        $data['time_expire'] = date("YmdHis", time() + $data['expire_time']);
        //$data['notify_url'] = "http://paysdk.weixin.qq.com/notify.php";//异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数。
        $data['notify_url'] = "http://www.easyko.cn/site/library/wxpay/notify.php";
        $data['product_id'] = $product['product_id'];

        // 入参
        $input = new WxPayUnifiedOrder();
        $input->SetBody($data['body']);
        $input->SetAttach($data['attach']);
        $input->SetOut_trade_no($data['out_trade_no']);
        $input->SetTotal_fee($data['total_fee']);
        $input->SetTime_start($data['time_start']);
        $input->SetTime_expire($data['time_expire']);
        //$input->SetGoods_tag("test"); // 订单优惠标志
        $input->SetNotify_url($data['notify_url']);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($data['product_id']);

        $result = $this->GetPayUrl($input);
        $data['url'] = $result["code_url"];
        $data['total_fee'] = $data['total_fee']/100;

        // 页面信息展示
        $view 			= $this->createView();
        $view->formhash = Config_App::formhash('checkout');
        $view->data 	= $data;
        $view->title	= $this->language['title'];
        $view->display('../checkout/payment.html');
    }

    /**
     *
     * 生成扫描支付URL,模式一
     * @param BizPayUrlInput $bizUrlInfo
     */
    public function GetPrePayUrl($productId)
    {
        $biz = new WxPayBizPayUrl();
        $biz->SetProduct_id($productId);
        try{
            $config = new WxPayConfig();
            $values = WxpayApi::bizpayurl($config, $biz);
        } catch(Exception $e) {
            Log::ERROR(json_encode($e));
        }
        $url = "weixin://wxpay/bizpayurl?" . $this->ToUrlParams($values);
        return $url;
    }

    /**
     *
     * 参数数组转换为url参数
     * @param array $urlObj
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     *
     * 生成直接支付url，支付url有效期为2小时,模式二
     * @param UnifiedOrderInput $input
     */
    public function GetPayUrl($input)
    {
        if($input->GetTrade_type() == "NATIVE")
        {
            try{
                $config = new WxPayConfig();
                $result = WxPayApi::unifiedOrder($config, $input);
                return $result;
            } catch(Exception $e) {
                Log::ERROR(json_encode($e));
            }
        }
        return false;
    }
}
?>