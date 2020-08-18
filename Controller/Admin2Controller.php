<?php

class Admin2Controller
{
	function __construct()
	{
		/*if (!$_SESSION['admin']) 
		{
			header('location:index.php?controller=UserController&action=adminlogin');
			exit;
		}*/

		$action = getIndex('action', 'index');

		if (method_exists($this, $action))//neu co ham action trong $this (class nay)
		{
			$reflection = new ReflectionMethod($this, $action);
		    if (!$reflection->isPublic()) {
		     //   throw new RuntimeException("The called method is not public.");
		     echo "No nha!";exit;
		    }
			$this->$action();
		}
		else 
		{
			echo "Chua xay dung...";
			exit;
		}
	}

	function index()
	{
		$m = new SachModel();
		$data = $m->getAll();
		$dataLoai = $m->getLoai();
		$dataNxb = $m->getNhaxb();
		include "View/Admin2Index.php";
	}

	function chiTietSach()
	{
		$id= getIndex('masach');
		$m= new SachModel();
		$data = $m->chiTiet($id);
		//print_r($data);
		echo json_encode($data);
	}

	function saveUpdate()
	{
		$m = new SachModel();
		echo $m->saveUpdate();
	}

	function saveInsert()
	{
		$m = new SachModel();
		echo $m->saveInsert();
	}
	function deleteSach()
	{
		//print_r($_GET);
		$m = new SachModel();
		$masach = getIndex('masach');
		echo $m->deleteSach($masach);
	}
}