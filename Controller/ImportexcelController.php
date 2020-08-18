<?php
use Phppot\DataSource;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ImportexcelController
{
	function __construct()
	{
		
		
		$action = getIndex('action', 'index');
		error_reporting(0);

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

	$m = new UserModel();
	$n = '';
	require_once ('vendor/autoload.php');

	if (isset($_POST["import"])) 
	{

    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
   

    if (in_array($_FILES["file"]["type"], $allowedFileType)) 
    {

        $targetPath = ROOT.'/uploads/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        $n = 0;
       // echo $targetPath;
        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        $sheetCount = count($spreadSheetAry);
        echo "Co $sheetCount dong ";

        //dong 0 la dong tieu de -> co the bo qua
        for ($i = 1; $i < $sheetCount; $i ++) 
        {
            $email	=	trim($spreadSheetAry[$i][0]);
            $matkhau  = md5(trim($spreadSheetAry[$i][1] ) );
            $tenkh	=	trim($spreadSheetAry[$i][2]);
            $diachi	=	trim($spreadSheetAry[$i][3]);
            $dienthoai	=	trim($spreadSheetAry[$i][4]);
            if (! empty($email) ) 
            {
               
                $insertId = $m->insert($email, $matkhau, $tenkh, $diachi, $dienthoai);
                
                $n +=$insertId;
               
            }
        }
    } 
}

	$result = $m->all();
	include 'View/importexcel_index.php';
}

}