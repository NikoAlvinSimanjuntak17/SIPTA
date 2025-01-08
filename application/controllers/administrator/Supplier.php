<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Supplier Controller
*| --------------------------------------------------------------------------
*| Supplier site
*|
*/
class Supplier extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_supplier');
	}

	/**
	* show all Suppliers
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('supplier_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['suppliers'] = $this->model_supplier->get($filter, $field, $this->limit_page, $offset);
		$this->data['supplier_counts'] = $this->model_supplier->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/supplier/index/',
			'total_rows'   => $this->model_supplier->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Supplier List');
		$this->render('backend/standart/administrator/supplier/supplier_list', $this->data);
	}
	
	/**
	* Add new suppliers
	*
	*/
	public function add()
	{
		$this->is_allowed('supplier_add');

		$this->template->title('Supplier New');
		$this->render('backend/standart/administrator/supplier/supplier_add', $this->data);
	}

	/**
	* Add New Suppliers
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('supplier_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'trim|required');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama_supplier' => $this->input->post('nama_supplier'),
				'alamat_lengkap' => $this->input->post('alamat_lengkap'),
				'no_telp' => $this->input->post('no_telp'),
			];

			
			$save_supplier = $this->model_supplier->store($save_data);

			if ($save_supplier) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_supplier;
					$this->data['message'] = 'Data telah berhasil disimpan. '.anchor('administrator/supplier/edit/' . $save_supplier, 'Edit Supplier').' or  '.anchor('administrator/supplier', ' Go back to list');
				} else {
					set_message('Data telah berhasil disimpan. '.anchor('administrator/supplier/edit/' . $save_supplier, 'Edit Supplier'), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/supplier');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/supplier');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Suppliers
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('supplier_update');

		$this->data['supplier'] = $this->model_supplier->find($id);

		$this->template->title('Supplier Update');
		$this->render('backend/standart/administrator/supplier/supplier_update', $this->data);
	}

	/**
	* Update Suppliers
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('supplier_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Maaf, Anda tidak memiliki izin untuk mengakses'
				]);
			exit;
		}
		
		$this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'trim|required');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama_supplier' => $this->input->post('nama_supplier'),
				'alamat_lengkap' => $this->input->post('alamat_lengkap'),
				'no_telp' => $this->input->post('no_telp'),
			];

			
			$save_supplier = $this->model_supplier->change($id, $save_data);

			if ($save_supplier) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = 'Data telah berhasil diperbarui. '.anchor('administrator/supplier', ' Go back to list');
				} else {
					set_message('Data telah berhasil diperbarui. ', 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/supplier');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = 'Your data not change';
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = 'Data not change';
					$this->data['redirect'] = base_url('administrator/supplier');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Suppliers
	*
	* @var $id String
	*/
	public function delete($id)
	{
		$this->is_allowed('supplier_delete');

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
            set_message('Supplier has been deleted.', 'success');
		} else {
            set_message('Error delete supplier.', 'error');
		}

		redirect('administrator/supplier');
	}

		/**
	* View view Suppliers
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('supplier_view');

		$this->data['supplier'] = $this->model_supplier->find($id);

		$this->template->title('Supplier Detail');
		$this->render('backend/standart/administrator/supplier/supplier_view', $this->data);
	}
	
	/**
	* delete Suppliers
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$supplier = $this->model_supplier->find($id);

		
		
		return $this->model_supplier->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('supplier_export');

		$this->model_supplier->export('supplier', 'supplier');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('supplier_export');

		$this->model_supplier->pdf('supplier', 'supplier');
	}
}


/* End of file supplier.php */
/* Location: ./application/controllers/administrator/Supplier.php */