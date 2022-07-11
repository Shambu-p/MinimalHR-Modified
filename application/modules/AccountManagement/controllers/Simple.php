<?php

class Simple extends MY_Controller {

    function __construct(){
        parent::__construct();
    }

    function message(){
        print "hello there";
    }

}
