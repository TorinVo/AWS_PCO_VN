<?php
class Routes_Controller extends Template_Controller {
	public $template;	
	public $Options;
	public $Member_id;
	public $InitService;
	
	public function __construct(){

		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		$this->_get_session_template();	

		$this->InitService = new Service_Controller();
		$this->Member_id = $this->sess_cus['id'];

		// $this->My_Update_Events();
		require Kohana::find_file('views/templates/pco/permission/','permission');
		require Kohana::find_file('views/templates/pco/options/','options');

	}

	private function _get_session_template(){

		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');	
	}
	
	public function __call($method, $arguments){
		
	}

	public function index()
	{
		$this->template->css = new View('routes/css');
		$this->template->content = new View('routes/index');
		$this->template->Jquery = new View('routes/js');
		// $_SESSION['TimeAuto']    = array();
	}

	public function getAllSet()
	{
		$set = $this->db->query('SELECT * FROM set_route')->result_array(false);
		$this->template = new View('routes/navSet');
		$this->template->jsLoad = new View('routes/loadRoutes');
		$this->template->set = $set;
		$this->template->render = true;

		echo $this->template;
		die();
	}

	public function LoadRoutes()
	{
		// echo 'vao';die();
		$idSet = $_POST['idSet'];
		// echo $idSet;die();
		$total = count($this->db->query('SELECT * FROM routes WHERE route_set = ' . $idSet)->result_array(false));
		ini_set('memory_limit', '-1');
		$_data            = array();
		$iSearch          = @$_POST['search'];
		$_isSearch        = ($iSearch == '')? false : true;
		$iDisplayLength   = (int)@($_POST['length']);
		$iDisplayStart    = (int)@($_POST['start']);
		// $sEcho            = (int)@($_POST['draw']);
		$total_items      = $total;
		$total_filter     = $total_items;

		$query_main = 'SELECT * FROM routes WHERE route_set = ' . $idSet;

		if($_isSearch)
			$query_main .= ' AND route_name LIKE "%'.$iSearch.'%"';

		// Limit
		$query_limit = ' LIMIT ' . $iDisplayStart . ', ' . $iDisplayLength;

		$filter = $this->db->query($query_main . $query_limit)->result_array(false);

		$total_filter = count($filter);

		// echo $this->getZIPString(1); die();
		// print_r($filter);die();

		foreach ($filter as $key => $value) {
			$_data[] = array(
				'tdID' => $value['route_id'],
				'tdNo' => $value['route_no'],
				'tdName' => $value['route_name'],
				'tdService' => $this->getService($value['route_id'],1),
				'tdMap' => $this->getMap($value['route_id']),
				'tdZIP' => $this->getZIPString($value['route_id']),
				'tdTechnician' => $this->getTechnicianName($value['route_technician'])
			);
		}

		$records                     = array();
		$records["data"]             = $_data;
		// $records["draw"]             = $sEcho;
		$records["recordsTotal"]     = $total_items;
		$records["recordsFiltered"]  = $total_filter;
		$records["_isSearch"]        = $_isSearch;
		echo json_encode($records);
		die();
	}
	public function loadZone()
	{
		$route_id = $_POST['route_id'];

		$route_info = $this->db->query('SELECT * FROM routes WHERE route_id = ' . $route_id)->result_array(false);
		$service = $this->db->query('SELECT * FROM customers_service WHERE service_route = ' . $route_id)->result_array(false);
		$customer_map = $this->db->query('SELECT * FROM route_map WHERE route_id = ' . $route_id)->result_array(false);
		$total_shape = $this->db->query('SELECT `date`,COUNT(*) as Total FROM route_map WHERE route_id = ' . $route_id . ' GROUP BY date')->result_array(false);
		$data = array(
			'map' => $customer_map,
			'total' => $total_shape,
			'service' => $service,
			'route_info' => $route_info
		);
		echo json_encode($data);
		die();
	}

	public function saveZone()
	{
		$data = $_POST['data'];
		$type = $_POST['type'];
		$id = $_POST['id'];

		$savePosition = array();
		$radius = 0;

		if(!empty($id))
		{
			switch ($type) {
				case 'circle': {
					array_push($savePosition,$data[0]['center']);
					$radius = $data[0]['radius'];
				}
					break;
				case 'rectangle':
				case 'polygon': {
					foreach ($data[0] as $value) {
						array_push($savePosition, $value);
					}
				}
					break;
			}
			$r_data = array();

			foreach ($savePosition as $key) {
				$_data = array(
			 		'route_id' => $id,
			 		'type' => strtoupper($type),
			 		'radius' => $radius,
			 		'latitude' => $key['lat'],
			 		'longitude' => $key['lng'],
			 		'date' => date('y-m-d H:i:s')
			 	);
			 	array_push($r_data,$_data);
			 	$row = $this->db->insert('route_map',$_data);
			}
			echo json_encode($r_data);
		}
		else
			echo 'No id route to save!';
		die();
	}

	public function test()
	{
		$this->template->css = new View('routes/css');
		$this->template->content = new View('routes/add_route');
		// $this->template->Jquery = new View('routes/js');
	}

	public function getNewSet()
	{
		$this->template = new View('routes/newSet');
		$this->template->render = true;
		$this->template->jsLoad = new View('routes/loadRoutes');
		$this->template->idSet = $_POST['idSet'];
		echo $this->template;
		die();
	}
	public function countAllSet(){
		$data = array();

		$setActive = $this->db->query('SELECT id FROM set_route WHERE active = 1')->result_array(false);
		$countSet = count($this->db->get('set_route')->result_array(false));

		array_push($data,$countSet,$setActive[0]);
		echo json_encode($data);
		die();
	}
	public function setActiveSet()
	{
		$setID = $_POST['setID'];

		$this->db->update('set_route',array('active' => 1),array('id' => $setID));
		$this->db->query('UPDATE set_route SET active = 0 WHERE id NOT 
			IN (' . $setID . ')');

		echo 'Success';
		die();
	}
	public function addRouteHtml()
	{
		$set = $_POST['idSet'];

		$technician = $this->db->query('SELECT * FROM _technician');

		$this->template = new View('routes/add_route');
		$this->template->idSet = $set;
		$this->template->technician = $technician;
		$this->template->render(true);

		die();
	}
	public function editRouteHtml()
	{
		$route = $_POST['idRoute'];
		$rs = $this->db->query('SELECT * FROM routes WHERE route_id = ' . $route)->result_array(false);
		$technician = $this->db->query('SELECT * FROM _technician');

		$this->template = new View('routes/edit_route');
		$this->template->technician = $technician;
		$this->template->route = $rs[0];
		$this->template->render(true);

		die();
	}

	public function insertRoute()
	{
		if(isset($_POST)){
			$no = $_POST['no'];
			$name = $_POST['name'];
			$zip = $_POST['zip'];
			$technician = $_POST['technician'];
			$idSet = $_POST['idSet'];

			$zip = explode(',',$zip);
			echo print_r($zip);die();

			$this->db->insert('routes', array(
				'route_no' => $no,
				'route_name' => $name,
				'route_set' => $idSet,
				'route_technician' => $technician
			));

			$lastID = $this->db->query('SELECT route_id FROM routes ORDER BY route_id DESC')->result_array(false);
			$id = $lastID[0]['route_id'];

			for($i = 0; $i < count($zip); $i++){
				$this->db->insert('route_zip', array(
					'route_id' => $id,
					'zip' => $zip[$i]
				));
			}

			echo true;
			die();
		}

		echo false;
		die();
	}

	public function checkRouteID()
	{
		if(isset($_POST['idRoute'])){
			$route = $_POST['idRoute'];

			$rs = $this->db->query('SELECT * FROM routes WHERE route_no = ' . $route)->result_array(false);
			if(count($rs) != 1)
				echo false;
			else
				echo true;
		}

		else
			echo false;
		
		die();
	}
	//orther function
	private function getService($route_id,$active = 0,$step = 0){
		$filter = $this->db->query('SELECT * FROM customers_service WHERE service_route = ' . $route_id)->result_array(false);
		if($step == 0)
			return count($filter);
		else
			return $filter;
	}
	private function getZIPString($route_id)
	{
		$filter = $this->db->query('SELECT * FROM customers_service WHERE service_route = ' . $route_id)->result_array(false);
		$str = '';

		foreach ($filter as $key => $value) {
			$str .= $value['service_zip'] . ', ';				
		}

		return $str;
	}
	private function getTechnicianName($technician_id)
	{
		$filter = $this->db->query('SELECT * FROM _technician WHERE id = ' . $technician_id)->result_array(false);

		foreach ($filter as $key => $value) {
			return $value['name'];
		}
	}
	private function getMap($route_id,$step = 0)
	{
		$filter = $this->db->query('SELECT * FROM route_map WHERE route_id = ' . $route_id)->result_array(false);
		if($step == 0){			
			return count($filter);
		}
		else{
			return $filter;
		}
	}
}