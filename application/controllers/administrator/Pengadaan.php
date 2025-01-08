<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Pengadaan Controller
*| --------------------------------------------------------------------------
*| Pengadaan site
*|
*/
class Pengadaan extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_pengadaan');
	}

	/**
	* show all Pengadaans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('pengadaan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['pengadaans'] = $this->model_pengadaan->get($filter, $field, $this->limit_page, $offset);
		$this->data['pengadaan_counts'] = $this->model_pengadaan->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/pengadaan/index/',
			'total_rows'   => $this->model_pengadaan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Pengadaan List');
		$this->render('backend/standart/administrator/pengadaan/pengadaan_list', $this->data);
	}
	
	/**
	* Add new pengadaans
	*
	*/
	public function add() {
		$this->is_allowed('pengadaan_add');
	
		// Barang yang belum diinput ke pengadaan
		$this->data['barang_unused'] = $this->model_pengadaan->get_unused_barang();
	
		$this->template->title('Pengadaan New');
		$this->render('backend/standart/administrator/pengadaan/pengadaan_add', $this->data);
	}
	
	/**
	* Add New Pengadaans
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('pengadaan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
			]);
			exit;
		}
	
		$this->form_validation->set_rules('tanggal_pengadaan', 'Tanggal Pengadaan', 'trim|required');
		$this->form_validation->set_rules('supplier', 'Supplier', 'trim|required');
		$this->form_validation->set_rules('jenis_pengadaan', 'Jenis Pengadaan', 'trim|required');
		$this->form_validation->set_rules('nama_barang[]', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('harga', 'Harga', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
	
		if ($this->form_validation->run()) {
			$nama_barang = $this->input->post('nama_barang'); // Array of selected barang
			$supplier = $this->input->post('supplier');
			$jenis_pengadaan = $this->input->post('jenis_pengadaan');
			$harga = $this->input->post('harga');
			$jumlah = $this->input->post('jumlah');
			$keterangan = $this->input->post('keterangan');
			$deskripsi_barang = $this->input->post('deskripsi_barang');
			$tanggal_pengadaan = $this->input->post('tanggal_pengadaan');
	
			foreach ($nama_barang as $barang) {
				$save_data = [
					'tanggal_pengadaan' => $tanggal_pengadaan,
					'supplier' => $supplier,
					'jenis_pengadaan' => $jenis_pengadaan,
					'keterangan' => $keterangan,
					'nama_barang' => $barang,
					'deskripsi_barang' => $deskripsi_barang,
					'harga' => $harga,
					'jumlah' => $jumlah,
				];
				$this->model_pengadaan->store($save_data);
			}
	
			$this->data['success'] = true;
			$this->data['redirect'] = base_url('administrator/pengadaan');
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}
	
		echo json_encode($this->data);
	}
	
	
		/**
	* Update view Pengadaans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('pengadaan_update');

		$this->data['pengadaan'] = $this->model_pengadaan->find($id);

		$this->template->title('Pengadaan Update');
		$this->render('backend/standart/administrator/pengadaan/pengadaan_update', $this->data);
	}

	/**
	* Update Pengadaans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('pengadaan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}
		
		$this->form_validation->set_rules('tanggal_pengadaan', 'Tanggal Pengadaan', 'trim|required');
		$this->form_validation->set_rules('supplier', 'Supplier', 'trim|required');
		$this->form_validation->set_rules('jenis_pengadaan', 'Jenis Pengadaan', 'trim|required');
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('harga', 'Harga', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'tanggal_pengadaan' => $this->input->post('tanggal_pengadaan'),
				'supplier' => $this->input->post('supplier'),
				'jenis_pengadaan' => $this->input->post('jenis_pengadaan'),
				'keterangan' => $this->input->post('keterangan'),
				'nama_barang' => $this->input->post('nama_barang'),
				'deskripsi_barang' => $this->input->post('deskripsi_barang'),
				'harga' => $this->input->post('harga'),
				'jumlah' => $this->input->post('jumlah'),
			];

			
			$save_pengadaan = $this->model_pengadaan->change($id, $save_data);

			if ($save_pengadaan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = 'Data telah berhasil diperbarui. '.anchor('administrator/pengadaan', ' Go back to list');
				} else {
					set_message('Data telah berhasil diperbarui. ', 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/pengadaan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/pengadaan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Pengadaans
	*
	* @var $id String
	*/
	public function delete($id)
	{
		$this->is_allowed('pengadaan_delete');

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
            set_message('Pengadaan has been deleted.', 'success');
		} else {
            set_message('Error delete pengadaan.', 'error');
		}

		redirect('administrator/pengadaan');
	}

		/**
	* View view Pengadaans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('pengadaan_view');

		$this->data['pengadaan'] = $this->model_pengadaan->find($id);

		$this->template->title('Pengadaan Detail');
		$this->render('backend/standart/administrator/pengadaan/pengadaan_view', $this->data);
	}
	
	/**
	* delete Pengadaans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$pengadaan = $this->model_pengadaan->find($id);

		
		
		return $this->model_pengadaan->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('pengadaan_export');

		$this->model_pengadaan->export('pengadaan', 'pengadaan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('pengadaan_export');

		$this->model_pengadaan->pdf('pengadaan', 'pengadaan');
	}
}


/* End of file pengadaan.php */
/* Location: ./application/controllers/administrator/Pengadaan.php */