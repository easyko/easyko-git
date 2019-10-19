<?php
class Fuse_Customer extends Fuse_Model {
    private $customer_id;
    private $firstname;
    private $lastname;
    private $email;
    private $telephone;
    private $fax;
    private $newsletter;
    private $customer_group_id;
    private $address_id;
    private $custom_field;
    private $username;

    public function __construct($options = array()) {
        parent::__construct($options);

        $this->session = Fuse_Session::getInstance();


        if (isset($this->session->data['customer_id'])) {
            $customer_query = $this->db->fetchRow("SELECT * FROM ek_user WHERE user_id = '" . (int)$this->session->data['customer_id'] . "'");
            if ($customer_query) {
                $this->customer_id = $customer_query['user_id'];
                /*
                $this->firstname = $customer_query->row['firstname'];
                $this->lastname = $customer_query->row['lastname'];
                $this->email = $customer_query->row['email'];
                $this->telephone = $customer_query->row['telephone'];
                $this->fax = $customer_query->row['fax'];
                $this->newsletter = $customer_query->row['newsletter'];
                $this->customer_group_id = $customer_query->row['customer_group_id'];
                $this->address_id = $customer_query->row['address_id'];
                $this->custom_field = unserialize($customer_query->row['custom_field']);
                $this->username = $this->custom_field['1'];
                $this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', cart_selected = '".$this->db->escape(isset($this->session->data['cart_selected']) ? serialize($this->session->data['cart_selected']) : '')."', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

                if (!$query->num_rows) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
                }
                */
            } else {
                $this->logout();
            }
        }

    }

    public static function getInstanceCustomer()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *
     * @param unknown $phone
     * @param unknown $password
     * @param string $override
     * @param string $preSHA1 是否前端已sha1预处理
     */
    public function login($phone, $password, $override = false, $preSHA1 = false) {
        $customer_query = '';
        if ($override) {
            // 验证码登录
            $customer_query = $this->db->fetchRow("SELECT * FROM ek_user WHERE mobile = '" . $phone . "'");   //"' AND status = '1'"
        } else {
            // 手机账号登录
            if (is_numeric($phone)) {
                if ($preSHA1){
                    $customer_query = $this->db->fetchRow("SELECT * FROM ek_user WHERE mobile = '" . $phone . "' AND (password = SHA1(CONCAT(rand_str, SHA1(CONCAT(rand_str, '" . $password . "')))))"); // AND status = '1' AND approved = '1'
                }
                else{
                    $customer_query = $this->db->fetchRow("SELECT * FROM ek_user WHERE mobile = '" . $phone . "' AND (password = SHA1(CONCAT(rand_str, SHA1(CONCAT(rand_str, SHA1('" . $password . "'))))) OR password = '" . md5($password) . "')");  // AND status = '1' AND approved = '1'
                }
            }
        }
        if ($customer_query) {
            $this->session->data['customer_id'] = $customer_query['user_id'];
            $this->session->data['username'] = $customer_query['username'];
            $this->session->data['company_id'] = $customer_query['company_id'];
            $this->customer_id = $customer_query['user_id'];
            $this->db->query("UPDATE ek_user SET last_login_ip = '" . $_SERVER['REMOTE_ADDR'] . "' WHERE user_id = '" . (int)$this->customer_id . "'");
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        unset($this->session->data['customer_id']);
        unset($this->session->data['username']);
        $this->customer_id = '';
        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->telephone = '';
        $this->fax = '';
        $this->newsletter = '';
        $this->customer_group_id = '';
        $this->address_id = '';
    }

    public function isLogged() {
        return $this->customer_id;
    }

    public function getId() {
        return $this->customer_id;
    }

    public function getFirstName() {
        return $this->firstname;
    }

    public function getLastName() {
        return $this->lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getFax() {
        return $this->fax;
    }

    public function getNewsletter() {
        return $this->newsletter;
    }

    public function getGroupId() {
        return $this->customer_group_id;
    }

    public function getAddressId() {
        return $this->address_id;
    }
    public function getCustomField() {
        return $this->custom_field;
    }
    public function getUserName() {
        return $this->username;
    }
    public function getBalance() {
        $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");

        return $query->row['total'];
    }

    public function getRewardPoints() {
        $query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");

        return $query->row['total'];
    }

    private function cartHasSelected(){
        if (empty($this->session->data['cart_selected'])){
            return false;
        }
        foreach ($this->session->data['cart_selected'] as $val){
            if ($val){
                return true;
            }
        }
        return false;
    }
}