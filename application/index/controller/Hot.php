<?php
namespace app\index\controller;
use think\Controller;

class Hot extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->label_fetch('hot/index');
    }
 

}
