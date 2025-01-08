<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Penempatan Controller
*| --------------------------------------------------------------------------
*| Penempatan site
*|
*/
class Penempatan extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_penempatan');
	}

	/**
	* show all Penempatans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('penempatan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['penempatans'] = $this->model_penempatan->get($filter, $field, $this->limit_page, $offset);
		$this->data['penempatan_counts'] = $this->model_penempatan->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/penempatan/index/',
			'total_rows'   => $this->model_penempatan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Penempatan List');
		$this->render('backend/standart/administrator/penempatan/penempatan_list', $this->data);
	}
	
	/**
	* Add new penempatans
	*
	*/
	public function add()
	{
		$this->is_allowed('penempatan_add');

		$this->template->title('Penempatan New');
		$this->render('backend/standart/administrator/penempatan/penempatan_add', $this->data);
	}

	/**
	* Add New Penempatans
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('penempatan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
			]);
			exit;
		}
	
		$this->form_validation->set_rules('tanggal_penempatan', 'Tanggal Penempatan', 'trim|required');
		$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required');
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			// Get the selected item name
			$nama_barang = $this->input->post('nama_barang');
			
			// Store the penempatan data
			$save_data = [
				'tanggal_penempatan' => $this->input->post('tanggal_penempatan'),
				'nama' => $this->input->post('nama'),
				'departemen' => $this->input->post('departemen'),
				'lokasi' => $this->input->post('lokasi'),
				'keterangan' => $this->input->post('keterangan'),
				'nama_barang' => $nama_barang,
				'jumlah' => $this->input->post('jumlah'),
			];
	
			$save_penempatan = $this->model_penempatan->store($save_data);
			
			if ($save_penempatan) {
				// Update the item's status to "penempatan"
				$this->db->update('barang', ['status' => 'penempatan'], ['nama_barang' => $nama_barang]);
	
				// Handle response
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] = $save_penempatan;
					$this->data['message'] = 'Data telah berhasil disimpan. '.anchor('administrator/penempatan/edit/' . $save_penempatan, 'Edit Penempatan').' or '.anchor('administrator/penempatan', 'Go back to list');
				} else {
					set_message('Data telah berhasil disimpan. '.anchor('administrator/penempatan/edit/' . $save_penempatan, 'Edit Penempatan'), 'success');
					$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/penempatan');
				}
			} else {
				$this->data['success'] = false;
				$this->data['message'] = 'Data not change';
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}
	
		echo json_encode($this->data);
	}
	
	
		/**
	* Update view Penempatans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('penempatan_update');

		$this->data['penempatan'] = $this->model_penempatan->find($id);

		$this->template->title('Penempatan Update');
		$this->render('backend/standart/administrator/penempatan/penempatan_update', $this->data);
	}

	/**
	* Update Penempatans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('penempatan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}
		
		$this->form_validation->set_rules('tanggal_penempatan', 'Tanggal Penempatan', 'trim|required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required');
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'tanggal_penempatan' => $this->input->post('tanggal_penempatan'),
				'nama' => $this->input->post('nama'),
				'departemen' => $this->input->post('departemen'),
				'lokasi' => $this->input->post('lokasi'),
				'keterangan' => $this->input->post('keterangan'),
				'nama_barang' => $this->input->post('nama_barang'),
				'jumlah' => $this->input->post('jumlah'),
			];

			
			$save_penempatan = $this->model_penempatan->change($id, $save_data);

			if ($save_penempatan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = 'Data telah berhasil diperbarui. '.anchor('administrator/penempatan', ' Go back to list');
				} else {
					set_message('Data telah berhasil diperbarui. ', 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/penempatan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/penempatan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Penempatans
	*
	* @var $id String
	*/
	public function delete($id)
	{
		$this->is_allowed('penempatan_delete');

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
            set_message('Penempatan has been deleted.', 'success');
		} else {
            set_message('Error delete penempatan.', 'error');
		}

		redirect('administrator/penempatan');
	}

		/**
	* View view Penempatans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('penempatan_view');

		$this->data['penempatan'] = $this->model_penempatan->find($id);

		$this->template->title('Penempatan Detail');
		$this->render('backend/standart/administrator/penempatan/penempatan_view', $this->data);
	}
	
	/**
	* delete Penempatans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$penempatan = $this->model_penempatan->find($id);

		
		
		return $this->model_penempatan->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('penempatan_export');

		$this->model_penempatan->export('penempatan', 'penempatan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('penempatan_export');

		$this->model_penempatan->pdf('penempatan', 'penempatan');
	}
}


/* End of file penempatan.php */
/* Location: ./application/controllers/administrator/Penempatan.php */