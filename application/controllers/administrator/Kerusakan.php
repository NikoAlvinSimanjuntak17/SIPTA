<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Kerusakan Controller
*| --------------------------------------------------------------------------
*| Kerusakan site
*|
*/
class Kerusakan extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_kerusakan');
	}

	/**
	* show all kerusakan
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('kerusakan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['kerusakans'] = $this->model_kerusakan->get($filter, $field, $this->limit_page, $offset);
		$this->data['kerusakan_counts'] = $this->model_kerusakan->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/kerusakan/index/',
			'total_rows'   => $this->model_kerusakan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Kerusakan List');
		$this->render('backend/standart/administrator/kerusakan/kerusakan_list', $this->data);
	}
	
	/**
	* Add new Kerusakans
	*
	*/
	public function add()
	{
		$this->is_allowed('kerusakan_add');

		$this->template->title('Kerusakan New');
		$this->render('backend/standart/administrator/kerusakan/kerusakan_add', $this->data);
	}

	/**
	* Add New Kerusakan
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('kerusakan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
			]);
			exit;
		}
	
		$this->form_validation->set_rules('tanggal_kerusakan', 'Tanggal Kerusakan', 'trim|required');
		$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required');
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			// Get the selected item name
			$nama_barang = $this->input->post('nama_barang');
			
			// Store the kerusakan data
			$save_data = [
				'tanggal_kerusakan' => $this->input->post('tanggal_kerusakan'),
				'nama' => $this->input->post('nama'),
				'departemen' => $this->input->post('departemen'),
				'lokasi' => $this->input->post('lokasi'),
				'keterangan' => $this->input->post('keterangan'),
				'nama_barang' => $nama_barang,
				'jumlah' => $this->input->post('jumlah'),
			];
	
			$save_kerusakan = $this->model_kerusakan->store($save_data);
			
			if ($save_kerusakan) {
				// Update the item's status to "kerusakan"
				$this->db->update('barang', ['status' => 'kerusakan'], ['nama_barang' => $nama_barang]);
	
				// Handle response
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] = $save_kerusakan;
					$this->data['message'] = 'Data telah berhasil disimpan. '.anchor('administrator/kerusakan/edit/' . $save_kerusakan, 'Edit kerusakan').' or '.anchor('administrator/kerusakan', 'Go back to list');
				} else {
					set_message('Data telah berhasil disimpan. '.anchor('administrator/kerusakan/edit/' . $save_kerusakan, 'Edit kerusakan'), 'success');
					$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/kerusakan');
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
	* Update view kerusakans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('kerusakan_update');

		$this->data['kerusakan'] = $this->model_kerusakan->find($id);

		$this->template->title('kerusakan Update');
		$this->render('backend/standart/administrator/kerusakan/kerusakan_update', $this->data);
	}

	/**
	* Update kerusakans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('kerusakan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}
		
		$this->form_validation->set_rules('tanggal_kerusakan', 'Tanggal kerusakan', 'trim|required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('departemen', 'Departemen', 'trim|required');
		$this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|required');
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'tanggal_kerusakan' => $this->input->post('tanggal_kerusakan'),
				'nama' => $this->input->post('nama'),
				'departemen' => $this->input->post('departemen'),
				'lokasi' => $this->input->post('lokasi'),
				'keterangan' => $this->input->post('keterangan'),
				'nama_barang' => $this->input->post('nama_barang'),
				'jumlah' => $this->input->post('jumlah'),
			];

			
			$save_kerusakan = $this->model_kerusakan->change($id, $save_data);

			if ($save_kerusakan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = 'Data telah berhasil diperbarui. '.anchor('administrator/kerusakan', ' Go back to list');
				} else {
					set_message('Data telah berhasil diperbarui. ', 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/kerusakan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/kerusakan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete kerusakans
	*
	* @var $id String
	*/
	public function delete($id)
	{
		$this->is_allowed('kerusakan_delete');

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
            set_message('kerusakan has been deleted.', 'success');
		} else {
            set_message('Error delete kerusakan.', 'error');
		}

		redirect('administrator/kerusakan');
	}

		/**
	* View view kerusakans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('kerusakan_view');

		$this->data['kerusakan'] = $this->model_kerusakan->find($id);

		$this->template->title('kerusakan Detail');
		$this->render('backend/standart/administrator/kerusakan/kerusakan_view', $this->data);
	}
	
	/**
	* delete kerusakans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$kerusakan = $this->model_kerusakan->find($id);

		
		
		return $this->model_kerusakan->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('kerusakan_export');

		$this->model_kerusakan->export('kerusakan', 'kerusakan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('kerusakan_export');

		$this->model_kerusakan->pdf('kerusakan', 'kerusakan');
	}
}


/* End of file kerusakan.php */
/* Location: ./application/controllers/administrator/kerusakan.php */