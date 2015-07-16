<?
/*
 * Created on 2008/10/02
 * Author Thao Tran
 * Main - Menu screen
 */
class Export extends Controller
{
	/**
	 * Controller constructor
	 * 2008/08/18
	 **/
	function __construct()
	{
		parent::Controller();
	}

	function index()
	{
		//require login
		/******************/
		$this->utils->requireLogin(true);
		/******************/
		$arrData = null;
		if (isset($_POST['download']))
		{
		    if(isset($_POST['filename']))
		    {
			    $pathfile = $_POST['filename'];
			    $filename = substr($pathfile, strrpos($pathfile, "/")+1);
			    header("Content-Type:application/csv"); 
			    header("Content-Disposition:attachment; filename=".$filename);
			    readfile($pathfile);
			    exit;
		    }
		}
		$this->load->view('export');
	}
}
?>
