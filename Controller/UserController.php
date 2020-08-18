<?php

class UserController
{
	function __construct()
	{
		

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

function adminlogin()
{
	//print_r($_POST);
	$u= postIndex('u');
	$p= md5(postIndex('p')) ;
	$m = new UserModel();//xu ly thong tin khach hang, quan tri
	$data = $m->getAdmin($u, $p);
	//print_r($data);
	if ($data==false)
		include 'View/UserAdminlogin.php';
	else
	{
		$_SESSION['admin']= $data;
		header('location:index.php?controller=AdminController');
	}
}

function adminlogout()
{
	unset($_SESSION['admin']);
	$this->adminlogin();
	//header('location:index.php?controller=UserController&action=adminlogin');
	//hoac
	//include 'View/UserAdminlogin.php';
}

function userLogin()
{
	//echo "Dang nhap";
	include 'View/userLogin.php';
}

function userLogin2()
{
	$email = postIndex('email');
	$matkhau = md5(postIndex('matkhau'));
	$m = new UserModel();
	$data =$m->getKhachhang($email, $matkhau);
	if ( $data==false)
	{
		header('location:index.php?controller=UserController&action=userLogin');
		exit;
	}
	else 
	{
		$_SESSION['userLogin']= $data;
		header('location:index.php');
	}
}

function userLogout()
{
	//echo "Dang xuat";
	unset($_SESSION['userLogin']);
	header('location:index.php');
}

function userRegister()
{
	echo "Dang ky";
}

function checkOut()
{
	$tam = isset($_SESSION['cart'])?$_SESSION['cart']:[];
		//print_r($tam);
		$data=[];//thong tin cac sp trong gio hang
		$m = new SachModel();
		foreach ($tam as $id => $sl) 
		{
			$sach= $m->chitiet($id);//lay chi tiet 1 sp
			if (!$sach) continue;
			$sach['soluong']= $sl;
			$data[]= $sach;

		}

	include 'View/userCheckout.php';
}

function finish()
{
	/*echo "Buoc cuoi cung";
	print_r($_POST);*/
	$m= new UserModel();
	$m->saveDonHang();
}

//=====================
function doimatkhau()
{
	//print_r($_SESSION);
	if (!isset($_SESSION['userLogin'])) exit;
	include 'View/user_doimatkhau.php';
}

function doimatkhau2()
{
	//print_r($_POST);
	if (!isset($_SESSION['userLogin'])) exit;
	//print_r($_SESSION);
	$email = $_SESSION['userLogin']['email'];

	$pw1 = postIndex('pw1');
	$pw2 = postIndex('pw2');
	$pw3 = postIndex('pw3');
	/*if ($pw2 != $pw3) 
	{
		echo 'mat khau moi va mkhau xac nhan khong trung nhau'; exit;
	}
	if ($pw1 == $pw2 )
	{
		echo 'Mkhau moi phai khac mkhau cu'; exit;
	}

	$m = new UserModel();
	$data =$m->getKhachhang($email, md5($pw1) );
	if ($data==false)//khong co khach nay - mkhau cu sai
	{
		echo 'Mkhau cu sai!'; exit;
	}
	$n = $m->doimatkhau($email, md5($pw2) );
	if ($n==1) echo "Doi mk thanh cong";
	else echo 'co loi gi do';*/
	$err='';
	if ($pw2 != $pw3) 
	{
		$err .= 'mat khau moi va mkhau xac nhan khong trung nhau<br>'; 
	}
	if ($pw1 == $pw2 )
	{
		$err .= 'Mkhau moi phai khac mkhau cu <br>'; //$err = $err .'....';
	}

	$m = new UserModel();
	$data =$m->getKhachhang($email, md5($pw1) );
	if ($data==false)//khong co khach nay - mkhau cu sai
	{
		$err .= '<br>Mkhau cu sai!'; 
	}

	if ($err !='')
	{
		echo $err; exit;
	}
	$n = $m->doimatkhau($email, md5($pw2) );
	if ($n==1) echo "Doi mk thanh cong";
	else echo 'co loi gi do';

}

function doimatkhau3()
{
	print_r($_POST);
}
}