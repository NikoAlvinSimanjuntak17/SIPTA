<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Form Pengajuan Pinjam Barang Controller
*| --------------------------------------------------------------------------
*| Form Pengajuan Pinjam Barang site
*|
*/
class Form_pengajuan_pinjam_barang extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_form_pengajuan_pinjam_barang');
	}

	/**
	* show all Form Pengajuan Pinjam Barangs
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('form_pengajuan_pinjam_barang_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['form_pengajuan_pinjam_barangs'] = $this->model_form_pengajuan_pinjam_barang->get($filter, $field, $this->limit_page, $offset);
		$this->data['form_pengajuan_pinjam_barang_counts'] = $this->model_form_pengajuan_pinjam_barang->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/form_pengajuan_pinjam_barang/index/',
			'total_rows'   => $this->model_form_pengajuan_pinjam_barang->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Pengajuan Pinjam Barang List');
		$this->render('backend/standart/administrator/form_builder/form_pengajuan_pinjam_barang/form_pengajuan_pinjam_barang_list', $this->data);
	}

	/**
	* Update view Form Pengajuan Pinjam Barangs
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('form_pengajuan_pinjam_barang_update');

		$this->data['form_pengajuan_pinjam_barang'] = $this->model_form_pengajuan_pinjam_barang->find($id);

		$this->template->title('Pengajuan Pinjam Barang Update');
		$this->render('backend/standart/administrator/form_builder/form_pengajuan_pinjam_barang/form_pengajuan_pinjam_barang_update', $this->data);
	}

	/**
	* Update Form Pengajuan Pinjam Barangs
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('form_pengajuan_pinjam_barang_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Sorry you do not have permission to access'
				]);
			exit;
		}
		
		$this->form_validation->set_rules('nipp', 'NIPP', 'trim|required');
		$this->form_validation->set_rules('nama_peminjam', 'Nama Peminjam', 'trim|required');
		$this->form_validation->set_rules('no_telp_hp', 'No Telp / Hp', 'trim|required');
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('dipakai_di', 'Dipakai Di', 'trim|required');
		$this->form_validation->set_rules('digunakan_untuk', 'Digunakan Untuk', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
		$this->form_validation->set_rules('tanggal_kembali', 'Tanggal Kembali', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'nipp' => $this->input->post('nipp'),
				'nama_peminjam' => $this->input->post('nama_peminjam'),
				'no_telp_hp' => $this->input->post('no_telp_hp'),
				'nama_barang' => $this->input->post('nama_barang'),
				'dipakai_di' => $this->input->post('dipakai_di'),
				'digunakan_untuk' => $this->input->post('digunakan_untuk'),
				'jumlah' => $this->input->post('jumlah'),
				'tanggal_pinjam' => date('Y-m-d H:i:s'),
				'tanggal_kembali' => $this->input->post('tanggal_kembali'),
			];

			
			$save_form_pengajuan_pinjam_barang = $this->model_form_pengajuan_pinjam_barang->change($id, $save_data);

			if ($save_form_pengajuan_pinjam_barang) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = 'Your data has been successfully updated into the database. '.anchor('administrator/form_pengajuan_pinjam_barang', ' Go back to list');
				} else {
					set_message('Your data has been successfully updated into the database. ', 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/form_pengajuan_pinjam_barang');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
				} else {
					set_message('Your data not change.', 'error');
					
            		$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
					$this->data['redirect'] = base_url('administrator/form_pengajuan_pinjam_barang');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}

	/**
	* delete Form Pengajuan Pinjam Barangs
	*
	* @var $id String
	*/
	public function delete($id)
	{
		$this->is_allowed('form_pengajuan_pinjam_barang_delete');

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
            set_message('Form Pengajuan Pinjam Barang has been deleted.', 'success');
		} else {
            set_message('Error delete form_pengajuan_pinjam_barang.', 'error');
		}

		redirect('administrator/form_pengajuan_pinjam_barang');
	}

	/**
	* View view Form Pengajuan Pinjam Barangs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('form_pengajuan_pinjam_barang_view');

		$this->data['form_pengajuan_pinjam_barang'] = $this->model_form_pengajuan_pinjam_barang->find($id);

		$this->template->title('Pengajuan Pinjam Barang Detail');
		$this->render('backend/standart/administrator/form_builder/form_pengajuan_pinjam_barang/form_pengajuan_pinjam_barang_view', $this->data);
	}

	/**
	* delete Form Pengajuan Pinjam Barangs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$form_pengajuan_pinjam_barang = $this->model_form_pengajuan_pinjam_barang->find($id);

		
		return $this->model_form_pengajuan_pinjam_barang->remove($id);
	}
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('form_pengajuan_pinjam_barang_export');

		$this->model_form_pengajuan_pinjam_barang->export('form_pengajuan_pinjam_barang', 'form_pengajuan_pinjam_barang');
	}
}


/* End of file form_pengajuan_pinjam_barang.php */
/* Location: ./application/controllers/administrator/Form Pengajuan Pinjam Barang.php */