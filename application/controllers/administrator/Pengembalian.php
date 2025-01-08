<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Pengembalian Controller
*| --------------------------------------------------------------------------
*| Pengembalian site
*|
*/
class Pengembalian extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_pengembalian');
	}

	/**
	* show all Pengembalians
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('pengembalian_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['pengembalians'] = $this->model_pengembalian->get($filter, $field, $this->limit_page, $offset);
		$this->data['pengembalian_counts'] = $this->model_pengembalian->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/pengembalian/index/',
			'total_rows'   => $this->model_pengembalian->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Pengembalian List');
		$this->render('backend/standart/administrator/pengembalian/pengembalian_list', $this->data);
	}
	
	/**
	* Add new pengembalians
	*
	*/
	public function add()
	{
		$this->is_allowed('pengembalian_add');

		$this->template->title('Pengembalian New');
		$this->render('backend/standart/administrator/pengembalian/pengembalian_add', $this->data);
	}

	/**
	* Add New Pengembalians
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('pengembalian_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('departemen_peminjam', 'Departemen Peminjam', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
		$this->form_validation->set_rules('tanggal_kembali', 'Tanggal Kembali', 'trim|required');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama_barang' => $this->input->post('nama_barang'),
				'tanggal_entry' => date('Y-m-d H:i:s'),
				'departemen_peminjam' => $this->input->post('departemen_peminjam'),
				'jumlah' => $this->input->post('jumlah'),
				'tanggal_kembali' => $this->input->post('tanggal_kembali'),
				'deskripsi' => $this->input->post('deskripsi'),
			];

			
			$save_pengembalian = $this->model_pengembalian->store($save_data);

			if ($save_pengembalian) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_pengembalian;
					$this->data['message'] = 'Data telah berhasil disimpan. '.anchor('administrator/pengembalian/edit/' . $save_pengembalian, 'Edit Pengembalian').' or  '.anchor('administrator/pengembalian', ' Go back to list');
				} else {
					set_message('Data telah berhasil disimpan. '.anchor('administrator/pengembalian/edit/' . $save_pengembalian, 'Edit Pengembalian'), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/pengembalian');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/pengembalian');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Pengembalians
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('pengembalian_update');

		$this->data['pengembalian'] = $this->model_pengembalian->find($id);

		$this->template->title('Pengembalian Update');
		$this->render('backend/standart/administrator/pengembalian/pengembalian_update', $this->data);
	}

	/**
	* Update Pengembalians
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('pengembalian_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}
		
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('departemen_peminjam', 'Departemen Peminjam', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
		$this->form_validation->set_rules('tanggal_kembali', 'Tanggal Kembali', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama_barang' => $this->input->post('nama_barang'),
				'tanggal_entry' => date('Y-m-d H:i:s'),
				'departemen_peminjam' => $this->input->post('departemen_peminjam'),
				'jumlah' => $this->input->post('jumlah'),
				'tanggal_kembali' => $this->input->post('tanggal_kembali'),
				'deskripsi' => $this->input->post('deskripsi'),
			];

			
			$save_pengembalian = $this->model_pengembalian->change($id, $save_data);

			if ($save_pengembalian) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = 'Data telah berhasil diperbarui. '.anchor('administrator/pengembalian', ' Go back to list');
				} else {
					set_message('Data telah berhasil diperbarui. ', 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/pengembalian');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/pengembalian');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Pengembalians
	*
	* @var $id String
	*/
	public function delete($id)
	{
		$this->is_allowed('pengembalian_delete');

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
            set_message('Pengembalian has been deleted.', 'success');
		} else {
            set_message('Error delete pengembalian.', 'error');
		}

		redirect('administrator/pengembalian');
	}

		/**
	* View view Pengembalians
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('pengembalian_view');

		$this->data['pengembalian'] = $this->model_pengembalian->find($id);

		$this->template->title('Pengembalian Detail');
		$this->render('backend/standart/administrator/pengembalian/pengembalian_view', $this->data);
	}
	
	/**
	* delete Pengembalians
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$pengembalian = $this->model_pengembalian->find($id);

		
		
		return $this->model_pengembalian->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('pengembalian_export');

		$this->model_pengembalian->export('pengembalian', 'pengembalian');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('pengembalian_export');

		$this->model_pengembalian->pdf('pengembalian', 'pengembalian');
	}
}


/* End of file pengembalian.php */
/* Location: ./application/controllers/administrator/Pengembalian.php */