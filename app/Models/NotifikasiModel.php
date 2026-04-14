<?php 

namespace App\Models; 

use CodeIgniter\Model;
class NotifikasiModel extends Model 
{
    protected $table='notifikasi'; 
    protected $primaryKey='id_notifikasi';
    protected $returnType='array'; 
    protected $useTimestamps=false;
    
    protected $allowedFields=['id_user','judul','pesan','link','is_read','created_at'];
}
