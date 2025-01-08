<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Jabatan Controller
*| --------------------------------------------------------------------------
*| Jabatan site
*|
*/
class Jabatan extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_jabatan');
	}

	/**
	* show all Jabatans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('jabatan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['jabatans'] = $this->model_jabatan->get($filter, $field, $this->limit_page, $offset);
		$this->data['jabatan_counts'] = $this->model_jabatan->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/jabatan/index/',
			'total_rows'   => $this->model_jabatan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Jabatan List');
		$this->render('backend/standart/administrator/jabatan/jabatan_list', $this->data);
	}
	
	/**
	* Add new jabatans
	*
	*/
	public function add()
	{
		$this->is_allowed('jabatan_add');

		$this->template->title('Jabatan New');
		$this->render('backend/standart/administrator/jabatan/jabatan_add', $this->data);
	}

	/**
	* Add New Jabatans
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('jabatan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}

		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required|max_length[50]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'jabatan' => $this->input->post('jabatan'),
			];

			
			$save_jabatan = $this->model_jabatan->store($save_data);

			if ($save_jabatan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_jabatan;
					$this->data['message'] = 'Data telah berhasil disimpan. '.anchor('administrator/jabatan/edit/' . $save_jabatan, 'Edit Jabatan').' or  '.anchor('administrator/jabatan', ' Go back to list');
				} else {
					set_message('Data telah berhasil disimpan. '.anchor('administrator/jabatan/edit/' . $save_jabatan, 'Edit Jabatan'), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/jabatan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/jabatan');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Jabatans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('jabatan_update');

		$this->data['jabatan'] = $this->model_jabatan->find($id);

		$this->template->title('Jabatan Update');
		$this->render('backend/standart/administrator/jabatan/jabatan_update', $this->data);
	}

	/**
	* Update Jabatans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('jabatan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}
		
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required|max_length[50]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'jabatan' => $this->input->post('jabatan'),
			];

			
			$save_jabatan = $this->model_jabatan->change($id, $save_data);

			if ($save_jabatan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = 'Data telah berhasil diperbarui. '.anchor('administrator/jabatan', ' Go back to list');
				} else {
					set_message('Data telah berhasil diperbarui. ', 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/jabatan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/jabatan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Jabatans
	*
	* @var $id String
	*/
	public function delete($id)
	{
		$this->is_allowed('jabatan_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message('Jabatan has been deleted.', 'success');
		} else {
            set_message('Error delete jabatan.', 'error');
		}

		redirect('administrator/jabatan');
	}

		/**
	* View view Jabatans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('jabatan_view');

		$this->data['jabatan'] = $this->model_jabatan->find($id);

		$this->template->title('Jabatan Detail');
		$this->render('backend/standart/administrator/jabatan/jabatan_view', $this->data);
	}
	
	/**
	* delete Jabatans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$jabatan = $this->model_jabatan->find($id);

		
		
		return $this->model_jabatan->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('jabatan_export');

		$this->model_jabatan->export('jabatan', 'jabatan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('jabatan_export');

		$this->model_jabatan->pdf('jabatan', 'jabatan');
	}
}


/* End of file jabatan.php */
/* Location: ./application/controllers/administrator/Jabatan.php */