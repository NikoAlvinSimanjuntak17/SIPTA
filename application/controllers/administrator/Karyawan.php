<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Karyawan Controller
*| --------------------------------------------------------------------------
*| Karyawan site
*|
*/
class Karyawan extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_karyawan');
	}

	/**
	* show all Karyawans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('karyawan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['karyawans'] = $this->model_karyawan->get($filter, $field, $this->limit_page, $offset);
		$this->data['karyawan_counts'] = $this->model_karyawan->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/karyawan/index/',
			'total_rows'   => $this->model_karyawan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Data Peminjam List');
		$this->render('backend/standart/administrator/karyawan/karyawan_list', $this->data);
	}
	
	/**
	* Add new karyawans
	*
	*/
	public function add()
	{
		$this->is_allowed('karyawan_add');

		$this->template->title('Data Peminjam New');
		$this->render('backend/standart/administrator/karyawan/karyawan_add', $this->data);
	}

	/**
	* Add New Karyawans
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('karyawan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('telp', 'Telp', 'trim|required');
		$this->form_validation->set_rules('nik', 'Nik', 'trim|required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'telp' => $this->input->post('telp'),
				'nik' => $this->input->post('nik'),
				'jabatan' => $this->input->post('jabatan'),
			];

			
			$save_karyawan = $this->model_karyawan->store($save_data);

			if ($save_karyawan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_karyawan;
					$this->data['message'] = 'Data telah berhasil disimpan. '.anchor('administrator/karyawan/edit/' . $save_karyawan, 'Edit Karyawan').' or  '.anchor('administrator/karyawan', ' Go back to list');
				} else {
					set_message('Data telah berhasil disimpan. '.anchor('administrator/karyawan/edit/' . $save_karyawan, 'Edit Karyawan'), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/karyawan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/karyawan');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Karyawans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('karyawan_update');

		$this->data['karyawan'] = $this->model_karyawan->find($id);

		$this->template->title('Data Peminjam Update');
		$this->render('backend/standart/administrator/karyawan/karyawan_update', $this->data);
	}

	/**
	* Update Karyawans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('karyawan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}
		
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('telp', 'Telp', 'trim|required');
		$this->form_validation->set_rules('nik', 'Nik', 'trim|required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama_lengkap' => $this->input->post('nama_lengkap'),
				'telp' => $this->input->post('telp'),
				'nik' => $this->input->post('nik'),
				'jabatan' => $this->input->post('jabatan'),
			];

			
			$save_karyawan = $this->model_karyawan->change($id, $save_data);

			if ($save_karyawan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = 'Data telah berhasil diperbarui. '.anchor('administrator/karyawan', ' Go back to list');
				} else {
					set_message('Data telah berhasil diperbarui. ', 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/karyawan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/karyawan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Karyawans
	*
	* @var $id String
	*/
	public function delete($id)
	{
		$this->is_allowed('karyawan_delete');

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
            set_message('Karyawan has been deleted.', 'success');
		} else {
            set_message('Error delete karyawan.', 'error');
		}

		redirect('administrator/karyawan');
	}

		/**
	* View view Karyawans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('karyawan_view');

		$this->data['karyawan'] = $this->model_karyawan->find($id);

		$this->template->title('Data Peminjam Detail');
		$this->render('backend/standart/administrator/karyawan/karyawan_view', $this->data);
	}
	
	/**
	* delete Karyawans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$karyawan = $this->model_karyawan->find($id);

		
		
		return $this->model_karyawan->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('karyawan_export');

		$this->model_karyawan->export('karyawan', 'karyawan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('karyawan_export');

		$this->model_karyawan->pdf('karyawan', 'karyawan');
	}
}


/* End of file karyawan.php */
/* Location: ./application/controllers/administrator/Karyawan.php */