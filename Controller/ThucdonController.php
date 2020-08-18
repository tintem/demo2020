<?php

class ThucdonController
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

	function index()
	{
		$m = new ThucdonModel();
		$dataMonan = $m->getMonan();
		include "View/Thucdon_index.php";
	}

	function save()
	{
		print_r($_POST);
		$m = new ThucdonModel();
		$m->saveThucDon();
	}
}