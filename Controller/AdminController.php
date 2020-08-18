<?php

class AdminController
{
	function __construct()
	{
		if (!$_SESSION['admin']) 
		{
			header('location:index.php?controller=UserController&action=adminlogin');
			exit;
		}

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
		$dataSach = $m->getAll();
		$dataLoai = $m->getLoai();
		include "View/AdminIndex.php";
	}

	function add()
	{
		$id = getIndex('id');
		$sl = getIndex('sl', 1);
		$tam = isset($_SESSION['cart'])?$_SESSION['cart']:[];
		if (isset($tam[$id]))
		{
			$tam[$id] =  $tam[$id] + $sl;
		}
		else $tam[$id]=$sl;

		$_SESSION['cart']= $tam;
		//header('location:index.php?controller=CartController');
		header('location:cart.html');
	}

	function delete()
	{
		$tam = isset($_SESSION['cart'])?$_SESSION['cart']:[];
		$id = getIndex('id');
		unset($tam[$id]);
		$_SESSION['cart']= $tam;
		header('location:index.php?controller=CartController');

	}

	function update()
	{

	}

	//-----------------------------------------------------------
	function book()
	{
		$m = new SachModel();
		$data = $m->getAll();
		$dataLoai = $m->getLoai();
		$dataNhaxb = $m->getNhaxb();
		include 'View/admin_book.php';
	}

	function book_detail()
	{
		$masach = $_GET['masach'];
		$m= new SachModel();
		$data =$m->chitiet($masach);
		echo json_encode($data);
	}

	function book_saveUpdate()
	{
		print_r($_POST);
		print_r($_FILES);
	}
}