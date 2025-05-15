<?php
session_start();
class Session{
    public function set($type, $value){
        
        $_SESSION[$type] = $value;
    }

    public function has($type){
        return isset($_SESSION[$type]);
    }

    public function remove($type){
        unset($_SESSION[$type]);
    }

    public function get($type, $save = false, $user = false){
        if($this->has($type)){
            $data = $_SESSION[$type];

            if(!$save && !$user){
                $this->remove($type);
            }

            return $data;
        }else{
            return false;
        }
    }

    public function getV2($type){
        return $_SESSION['user']->$type;
    }
}