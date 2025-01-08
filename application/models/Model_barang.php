<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_barang extends MY_Model {

	private $primary_key 	= 'id_barang';
	private $table_name 	= 'barang';
	private $field_search = ['nama_barang', 'sn', 'kategori', 'jumlah', 'satuan', 'gambar', 'status'];

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	public function count_all($q = null, $field = null)
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= $field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . $field . " LIKE '%" . $q . "%' )";
        }

        $this->db->where($where);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= $field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . $field . " LIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }

        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}
	// In your Model_barang (or another appropriate model), you can add a method to get the history data:

public function get_history($nama_barang)
{
	    // Retrieve penempatan data based on nama_barang
		$this->db->select('id_pengajuan, tgl_pinjam, nama, departemen, lokasi, keperluan');
		$this->db->from('pengajuan');
		$this->db->where('nama_barang', $nama_barang);
		$peminjaman = $this->db->get()->result();

    // Retrieve penempatan data based on nama_barang
    $this->db->select('id_penempatan, tanggal_penempatan, nama, departemen, lokasi, keterangan');
    $this->db->from('penempatan');
    $this->db->where('nama_barang', $nama_barang);
    $penempatan = $this->db->get()->result();

    // Retrieve kerusakan data based on nama_barang
    $this->db->select('id_kerusakan, tanggal_kerusakan, nama, departemen, lokasi, keterangan');
    $this->db->from('kerusakan');
    $this->db->where('nama_barang', $nama_barang);
    $kerusakan = $this->db->get()->result();

    // Retrieve mutasi data based on nama_barang
    $this->db->select('id_mutasi, tanggal_mutasi, keterangan');
    $this->db->from('mutasi');
    $this->db->where('nama_barang', $nama_barang);
    $mutasi = $this->db->get()->result();

    // Return all data together
    return [
        'penempatan' => $penempatan,
        'kerusakan' => $kerusakan,
        'mutasi' => $mutasi,
		'peminjaman' => $peminjaman
    ];
}


}

/* End of file Model_barang.php */
/* Location: ./application/models/Model_barang.php */