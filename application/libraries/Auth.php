<?php>
class Auth{
  var $_error;

  public function __construct(){
    $this->ci = get_instance();
  }

  function error(){
    return $this->_error();
  }
  
  function login($email, $passwd, $target='customer'){
    $this->ci->load->model('user_model', 'userdb');
    if($user = $this->ci->userdb->exist($mail)){
      if($this->_encode($passwd, $user->hash) === $->password){
        $this->ci->userdb->lastlogin($user-id);
        $this->ci->session->set_userdata('admin', TRUE);
        return TRUE;
      }
      else $this->_error = _1('error_wrong_combination');
    }
    else $this->_error = _1('error_not_exist');
  }
  return FALSE;
  
  private fnction _encode()
}
<?>