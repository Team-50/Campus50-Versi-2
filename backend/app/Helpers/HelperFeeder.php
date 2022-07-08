<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;

use Exception;

class HelperFeeder
{
  const LOG_CHANNEL = 'feeder';
  const FILE_KONEKSI = 'koneksi.json';

  private $FEEDER_WEB_URL;
  private $FEEDER_API_URL;
  private $FEEDER_USERNAME;
  private $FEEDER_PASSWORD;
  private $TOKEN;
  private $RESPONSE;

  public function __construct($token = null)
  {
    $this->FEEDER_WEB_URL = env('FEEDER_WEB_URL', 'xxx');
    $this->FEEDER_API_URL = env('FEEDER_API_URL', 'xxx');
    $this->FEEDER_USERNAME = env('FEEDER_USERNAME', 'xxx');
    $this->FEEDER_PASSWORD = env('FEEDER_PASSWORD', 'xxx');
    $this->TOKEN = $token;
  }

  /**
   * digunakan untuk mendapatkan feeder web url
   */
  public function getFeederWeb()
  {
    return $this->FEEDER_WEB_URL;
  }
  /**
   * digunakan untuk mendapatkan feeder api url
   */
  public function getFeederAPI()
  {
    return $this->FEEDER_API_URL;
  }
  /**
   * digunakan untuk mendapatkan feeder username
   */
  public function getFeederUsername()
  {
    return $this->FEEDER_USERNAME;
  }
  /**
   * digunakan untuk mendapatkan feeder password
   */
  public function getFeederPassword()
  {
    return $this->FEEDER_PASSWORD;
  }
  /**
   * digunakan untuk melakukan http request
   */
  private function HttpPost($url, $rawBody)
  { 
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    $headers = array(
      "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $rawBody);

    $response = curl_exec($curl);
    curl_close($curl);
    $this->RESPONSE = json_decode($response, true);

    return $this->RESPONSE;
  }
  /**
   * @return Http response
   */
  public function koneksi()
  {
    $rawBody = json_encode([
      'act'=>'GetToken',
      'username'=>$this->getFeederUsername(),
      'password'=>$this->getFeederPassword(),
    ]);    
    return $this->HttpPost($this->getFeederAPI() .'?=&=', $rawBody);    
  }
  /**
   *  digunakan untuk mendapatkan token dari file koneksi.json
   * @return string koneksi
  */
  public function getKoneksi() {
    if (!Storage::disk('local')->has('feeder/' . self::FILE_KONEKSI)) 
    {
      \Log::channel(self::LOG_CHANNEL)->error("HelperFeeder::koneksi(".$this->getFeederAPI().")");
      return null;
    } 
    $content = Storage::disk('local')->get('feeder/' . self::FILE_KONEKSI);
    if ($content == null)
    {
      \Log::channel(self::LOG_CHANNEL)->error("HelperFeeder::koneksi(".$this->getFeederAPI().")");
      return null;
    } 
    else
    {
      $koneksi = json_decode($content, true);
      
      if (!isset($koneksi['error_code']))
      {
        \Log::channel(self::LOG_CHANNEL)->error("HelperFeeder::koneksi(".$this->getFeederAPI().")");
        return null;
      } 
      else
      {
        switch($koneksi['error_code'])
        {
          case 0:
            $this->TOKEN = $koneksi['data']['token'];
            return $koneksi['data']['token'];
          break;
          default:
            \Log::channel(self::LOG_CHANNEL)->error("HelperFeeder::koneksi(".$this->getFeederAPI().")");
            return null;
        }
      }      
    }
  }  
  /**
   * digunakan untuk mendapatkan list program studi
   * {
   * "act":"GetProdi",
   * "token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZF9wZW5nZ3VuYSI6ImRjNjE0ZjMxLTU0ODItNDI1Ny04MGVlLWU4NTA1NGY5NWIyOCIsInVzZXJuYW1lIjoiMDcxMDA3IiwicGFzc3dvcmQiOiI3YzYxZWIxZDZkYjE0MWRmZDhiM2E3MWRhYzI2NjQ4MjQ2YmExNjFkIiwibm1fcGVuZ2d1bmEiOiIgVU5JVkVSU0lUQVMgU1VOQU4gR0lSSSAgICAgICAgICAgICAgICAgIiwidGVtcGF0X2xhaGlyIjoiICIsInRnbF9sYWhpciI6IjE4OTktMTItMzFUMTY6NTI6NDguMDAwWiIsImplbmlzX2tlbGFtaW4iOiJMIiwiYWxhbWF0IjoiSmwuIEJyaWdqZW4gS2F0YW1zbyBJSSBXYXJ1IFNpZG9hcmpvIEphd2EgVGltdXIiLCJ5bSI6IiAiLCJza3lwZSI6IiAiLCJub190ZWwiOiIgIiwibm9faHAiOiIwMzEgODUzMjQ3NyIsImFwcHJvdmFsX3BlbmdndW5hIjoiMSIsImFfYWt0aWYiOiIxIiwidGdsX2dhbnRpX3B3ZCI6bnVsbCwiaWRfc2RtX3BlbmdndW5hIjpudWxsLCJpZF9wZF9wZW5nZ3VuYSI6bnVsbCwiaWRfd2lsIjoiOTk5OTk5ICAiLCJsYXN0X3VwZGF0ZSI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsInNvZnRfZGVsZXRlIjoiMCIsImxhc3Rfc3luYyI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsImlkX3VwZGF0ZXIiOiJkYzYxNGYzMS01NDgyLTQyNTctODBlZS1lODUwNTRmOTViMjgiLCJjc2YiOiIxMTM3NjIzOTQ1IiwidG9rZW5fcmVnIjpudWxsLCJqYWJhdGFuIjpudWxsLCJ0Z2xfY3JlYXRlIjoiMTk2OS0xMi0zMVQxNzowMDowMC4wMDBaIiwiaWRfcGVyYW4iOjMsIm5tX3BlcmFuIjoiQWRtaW4gUFQiLCJpZF9zcCI6IjFhOGQzYjcyLTY5YzQtNDFjMy1hZTdkLWVhOWJhZDZhODYwYyIsImlhdCI6MTY0MDIzOTU2MiwiZXhwIjoxNjQwMjQxMzYyfQ._ukqUAp8tCovJYYnpze15-P9NsM42ktxcU-ZsIt7SJw",
   * "filter":""
   * }
  */
  public function getProdiForFilter($filter = null)
  {
    $rawBody = json_encode([
      'act'=>'GetProdi',
      'token'=>$this->TOKEN,
      'filter'=>$filter,      
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);        
    $daftar_prodi[''] = 'DAFTAR PROGRAM STUDI';
    if (!is_null($result))
    {
      $data = $result['data'];
      if ($result['error_code'] == 0)
      {
        foreach($data as $v)
        {        
          $daftar_prodi[$v['id_prodi']] = $v['nama_jenjang_pendidikan'] . ' '. $v['nama_program_studi'];
        }
      }      
    }
    return $daftar_prodi;
  }  
  /**
   * digunakan untuk mendapatkan data satu buah prodi
   */
  public function getDataProdi($id_prodi)
  {
    $rawBody = json_encode([
      'act'=>'GetProdi',
      'token'=>$this->TOKEN,
      'filter'=>"id_prodi='$id_prodi'",      
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);
    
    if ($result['error_code'] == 0)
    {
      return $result['data'][0];
    }
    else
    {
      throw new Exception ($result['error_code'] .' '. $result['error_desc']);
    }    
  }
  /**
   * digunakan untuk mendapatkan list semester
   * {
   * "act":"GetSemester",
   * "token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZF9wZW5nZ3VuYSI6ImRjNjE0ZjMxLTU0ODItNDI1Ny04MGVlLWU4NTA1NGY5NWIyOCIsInVzZXJuYW1lIjoiMDcxMDA3IiwicGFzc3dvcmQiOiI3YzYxZWIxZDZkYjE0MWRmZDhiM2E3MWRhYzI2NjQ4MjQ2YmExNjFkIiwibm1fcGVuZ2d1bmEiOiIgVU5JVkVSU0lUQVMgU1VOQU4gR0lSSSAgICAgICAgICAgICAgICAgIiwidGVtcGF0X2xhaGlyIjoiICIsInRnbF9sYWhpciI6IjE4OTktMTItMzFUMTY6NTI6NDguMDAwWiIsImplbmlzX2tlbGFtaW4iOiJMIiwiYWxhbWF0IjoiSmwuIEJyaWdqZW4gS2F0YW1zbyBJSSBXYXJ1IFNpZG9hcmpvIEphd2EgVGltdXIiLCJ5bSI6IiAiLCJza3lwZSI6IiAiLCJub190ZWwiOiIgIiwibm9faHAiOiIwMzEgODUzMjQ3NyIsImFwcHJvdmFsX3BlbmdndW5hIjoiMSIsImFfYWt0aWYiOiIxIiwidGdsX2dhbnRpX3B3ZCI6bnVsbCwiaWRfc2RtX3BlbmdndW5hIjpudWxsLCJpZF9wZF9wZW5nZ3VuYSI6bnVsbCwiaWRfd2lsIjoiOTk5OTk5ICAiLCJsYXN0X3VwZGF0ZSI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsInNvZnRfZGVsZXRlIjoiMCIsImxhc3Rfc3luYyI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsImlkX3VwZGF0ZXIiOiJkYzYxNGYzMS01NDgyLTQyNTctODBlZS1lODUwNTRmOTViMjgiLCJjc2YiOiIxMTM3NjIzOTQ1IiwidG9rZW5fcmVnIjpudWxsLCJqYWJhdGFuIjpudWxsLCJ0Z2xfY3JlYXRlIjoiMTk2OS0xMi0zMVQxNzowMDowMC4wMDBaIiwiaWRfcGVyYW4iOjMsIm5tX3BlcmFuIjoiQWRtaW4gUFQiLCJpZF9zcCI6IjFhOGQzYjcyLTY5YzQtNDFjMy1hZTdkLWVhOWJhZDZhODYwYyIsImlhdCI6MTY0MDIzOTU2MiwiZXhwIjoxNjQwMjQxMzYyfQ._ukqUAp8tCovJYYnpze15-P9NsM42ktxcU-ZsIt7SJw",
   * "filter":""
   * }
  */
  public function getSemester($order = null, $limit = 3)
  {
    $ta = \HelperPage::getDefaultTA();
    $rawBody = json_encode([
      'act'=>'GetSemester',
      'token'=>$this->TOKEN,
      'filter'=>"id_tahun_ajaran <= $ta",
      'order'=>$order,
      'limit'=>$limit,      
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);
    
    $daftar_semester[''] = 'DAFTAR SEMESTER';
    if (!is_null($result))
    {
      $data = $result['data'];
      if ($result['error_code'] == 0)
      {
        foreach($data as $v)
        {        
          $daftar_semester[$v['id_semester']] = $v['nama_semester'];
        }
      }      
    }
    return $daftar_semester;
  }  
   /**
   * digunakan untuk mendapatkan jumlah daftar kurikulum
   */
  public function getCountKurikulum($limit = null, $offset = null, $order = null, $filter = null)
  {
    $rawBody = json_encode([
      'act'=>'GetCountKurikulum',
      'token'=>$this->TOKEN,
      'limit'=>$limit,
      'offset'=>$offset,
      'order'=>$order,
      'filter'=>$filter,
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);
    
    if ($result['error_code'] == 0)
    {
      return (int)$result['data'];
    }
    else
    {
      throw new Exception ($result['error_code'] .' '. $result['error_desc']);
    }    
  }
  /**
   * digunakan untuk mendapatkan list data
   */
  public function list($act, $limit = null, $offset = null, $order = null, $filter = null)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'limit'=>$limit,
      'offset'=>$offset,
      'order'=>$order,
      'filter'=>$filter,
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);
    
    if ($result['error_code'] == 0)
    {
      return $result['data'];
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan dengan pesan: " .$result['error_code'] .' '. $result['error_desc']);
    }      
  }
  /**
   * digunakan untuk mendapakan detail record
   */
  public function detail($act, $filter)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'filter'=>$filter
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);

    if ($result['error_code'] == 0)
    {
      return $result['data'][0];
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan dengan pesan: " . $result['error_code'] .' '. $result['error_desc']);
    }     
  }
  /**
   * digunakan untuk menginputkan resource baru
   */
  public function store($act, $record, $job_id = null)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'record'=>$record,      
    ]);   

    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);

    if (isset($result['error_code']))
    {
      switch($result['error_code'])
      {
        case '0':
        case '630':
          return $result;
        break;        
        default:        
          $desc = "Proses $act gagal dilakukan dengan pesan: " . $result['error_code'] .' '. $result['error_desc']. ' key '.json_encode($key) . ' record '.json_encode($record);
          if (!is_null($job_id))
          {
            \DB::table('pe3_feeder_job')
            ->where('id', $job_id)
            ->update([
              'status' => 2,
              'desc' => $desc,
            ]);
          }
          throw new Exception ($desc . " job id: $job_id"); 
      }         
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan, dengan pesan $result");;
    }
  }
  /**
   * digunakan untuk merubah resource
   */
  public function update($act, $key, $record, $job_id = null)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'key'=>$key,
      'record'=>$record,      
    ]);   

    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);

    if (isset($result['error_code']))
    {
      switch($result['error_code'])
      {
        case '0':
          return $result;
        break;        
        default:
          $desc = "Proses $act gagal dilakukan dengan pesan: " . $result['error_code'] .' '. $result['error_desc']. ' key '.json_encode($key) . ' record '.json_encode($record);
          if (!is_null($job_id))
          {
            \DB::table('pe3_feeder_job')
            ->where('id', $job_id)
            ->update([
              'status' => 2,
              'desc' => $desc,
            ]);
          }
          throw new Exception ($desc . " job id: $job_id");          
      }         
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan, dengan pesan $result");;
    }
  }
  /**
   * digunakan untuk menghapus resource
   */
  public function destroy($act, $key, $job_id = null)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'key'=>$key,      
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);
    if (isset($result['error_code']))
    {
      if ($result['error_code'] == '0')
      {
        return $result;
      }
      else
      {
        $desc = "Proses $act gagal dilakukan dengan pesan: " . $result['error_code'] .' '. $result['error_desc'];
        if (!is_null($job_id))
        {
          \DB::table('pe3_feeder_job')
          ->where('id', $job_id)
          ->update([
            'status' => 2,
            'desc' => $desc,
          ]);
        }
        throw new Exception ($desc . " job id: $job_id");
      }
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan, dengan pesan $result");;
    }    
  }  
  /**
   * 
   * digunakan untuk mendapakatn jumlah record nilai mahasiswa atau bisa digunakan juga untuk 
   * mengetahui jumlah record dari KRS
   * keterangan filter bisa mendapatkan ide dari GetRiwayatNilaiMahasiswa
   * {
   * "act":"GetCountRiwayatNilaiMahasiswa",
   * "token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZF9wZW5nZ3VuYSI6ImRjNjE0ZjMxLTU0ODItNDI1Ny04MGVlLWU4NTA1NGY5NWIyOCIsInVzZXJuYW1lIjoiMDcxMDA3IiwicGFzc3dvcmQiOiI3YzYxZWIxZDZkYjE0MWRmZDhiM2E3MWRhYzI2NjQ4MjQ2YmExNjFkIiwibm1fcGVuZ2d1bmEiOiIgVU5JVkVSU0lUQVMgU1VOQU4gR0lSSSAgICAgICAgICAgICAgICAgIiwidGVtcGF0X2xhaGlyIjoiICIsInRnbF9sYWhpciI6IjE4OTktMTItMzFUMTY6NTI6NDguMDAwWiIsImplbmlzX2tlbGFtaW4iOiJMIiwiYWxhbWF0IjoiSmwuIEJyaWdqZW4gS2F0YW1zbyBJSSBXYXJ1IFNpZG9hcmpvIEphd2EgVGltdXIiLCJ5bSI6IiAiLCJza3lwZSI6IiAiLCJub190ZWwiOiIgIiwibm9faHAiOiIwMzEgODUzMjQ3NyIsImFwcHJvdmFsX3BlbmdndW5hIjoiMSIsImFfYWt0aWYiOiIxIiwidGdsX2dhbnRpX3B3ZCI6bnVsbCwiaWRfc2RtX3BlbmdndW5hIjpudWxsLCJpZF9wZF9wZW5nZ3VuYSI6bnVsbCwiaWRfd2lsIjoiOTk5OTk5ICAiLCJsYXN0X3VwZGF0ZSI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsInNvZnRfZGVsZXRlIjoiMCIsImxhc3Rfc3luYyI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsImlkX3VwZGF0ZXIiOiJkYzYxNGYzMS01NDgyLTQyNTctODBlZS1lODUwNTRmOTViMjgiLCJjc2YiOiIxMTM3NjIzOTQ1IiwidG9rZW5fcmVnIjpudWxsLCJqYWJhdGFuIjpudWxsLCJ0Z2xfY3JlYXRlIjoiMTk2OS0xMi0zMVQxNzowMDowMC4wMDBaIiwiaWRfcGVyYW4iOjMsIm5tX3BlcmFuIjoiQWRtaW4gUFQiLCJpZF9zcCI6IjFhOGQzYjcyLTY5YzQtNDFjMy1hZTdkLWVhOWJhZDZhODYwYyIsImlhdCI6MTY0MDIzOTU2MiwiZXhwIjoxNjQwMjQxMzYyfQ._ukqUAp8tCovJYYnpze15-P9NsM42ktxcU-ZsIt7SJw",
   * "filter":""
   * }
  */
  public function getCountRiwayatNilaiMahasiswa($filter = null)
  {
    $rawBody = json_encode([
      'act'=>'GetCountRiwayatNilaiMahasiswa',
      'token'=>$this->TOKEN,
      'filter'=>$filter,      
    ]);

    return $this->HttpPost($this->getFeederAPI(), $rawBody);
  }
  /**
   * digunakan untuk mendapatkan krs mahasiswa
   * feeder format query
   * {
   * "act":"GetKRSMahasiswa",
   * "token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZF9wZW5nZ3VuYSI6ImRjNjE0ZjMxLTU0ODItNDI1Ny04MGVlLWU4NTA1NGY5NWIyOCIsInVzZXJuYW1lIjoiMDcxMDA3IiwicGFzc3dvcmQiOiI3YzYxZWIxZDZkYjE0MWRmZDhiM2E3MWRhYzI2NjQ4MjQ2YmExNjFkIiwibm1fcGVuZ2d1bmEiOiIgVU5JVkVSU0lUQVMgU1VOQU4gR0lSSSAgICAgICAgICAgICAgICAgIiwidGVtcGF0X2xhaGlyIjoiICIsInRnbF9sYWhpciI6IjE4OTktMTItMzFUMTY6NTI6NDguMDAwWiIsImplbmlzX2tlbGFtaW4iOiJMIiwiYWxhbWF0IjoiSmwuIEJyaWdqZW4gS2F0YW1zbyBJSSBXYXJ1IFNpZG9hcmpvIEphd2EgVGltdXIiLCJ5bSI6IiAiLCJza3lwZSI6IiAiLCJub190ZWwiOiIgIiwibm9faHAiOiIwMzEgODUzMjQ3NyIsImFwcHJvdmFsX3BlbmdndW5hIjoiMSIsImFfYWt0aWYiOiIxIiwidGdsX2dhbnRpX3B3ZCI6bnVsbCwiaWRfc2RtX3BlbmdndW5hIjpudWxsLCJpZF9wZF9wZW5nZ3VuYSI6bnVsbCwiaWRfd2lsIjoiOTk5OTk5ICAiLCJsYXN0X3VwZGF0ZSI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsInNvZnRfZGVsZXRlIjoiMCIsImxhc3Rfc3luYyI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsImlkX3VwZGF0ZXIiOiJkYzYxNGYzMS01NDgyLTQyNTctODBlZS1lODUwNTRmOTViMjgiLCJjc2YiOiIxMTM3NjIzOTQ1IiwidG9rZW5fcmVnIjpudWxsLCJqYWJhdGFuIjpudWxsLCJ0Z2xfY3JlYXRlIjoiMTk2OS0xMi0zMVQxNzowMDowMC4wMDBaIiwiaWRfcGVyYW4iOjMsIm5tX3BlcmFuIjoiQWRtaW4gUFQiLCJpZF9zcCI6IjFhOGQzYjcyLTY5YzQtNDFjMy1hZTdkLWVhOWJhZDZhODYwYyIsImlhdCI6MTYzOTkyMzYyNiwiZXhwIjoxNjM5OTI1NDI2fQ.MJLZxuhhOYO0gu_D2G_vPfxFcx-Zkm6l7rIcSbhcRRs",
   * "filter":"nama_program_studi like 'S1%'",
   * "order":"",
   * "limit":"1",
   * "offset":"0"
   * }
  */
  public function getKRSMahasiswa($order='', $limit = 1, $offset=0, $filter=null)
  {
    $rawBody = json_encode([
      'act'=>'GetKRSMahasiswa',
      'token'=>$this->TOKEN,
      'filter'=>$filter,
      'order'=>$order,
      'limit'=>$limit,
      'offset'=>$offset,
    ]);

    return $this->HttpPost($this->getFeederAPI(), $rawBody);
  }
  
}