<?php
namespace Admin\Controller;


class IndexController extends CommonController
{
    public function index()
    {
        $this->show('hello world');
    }
}