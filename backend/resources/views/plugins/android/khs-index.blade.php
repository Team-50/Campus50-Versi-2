<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1,maximum-scale=1">
  <link rel="stylesheet" href="[css/bootstrap/bootstrap.min.css">
</head>
<body>
<a href="/{simak}/khs?nim=3218703&idsmt=2&ta=2019">Test</a>
<table class="">
  <tr>
    <td width="150">TAHUN AKADEMIK</td>
    <td></td>
  </tr>
</table>
<table class="table">
  <thead>
    <tr>
      <th>NO</th>
      <th>KODE</th>
      <th>NAMA</th>
      <th>SKS</th>
      <th>NILAI</th>
    </tr>
  </thead>
  <tbody>    
  @foreach($daftar_khs as $k=>$v)
    <tr>
      <td>{{ $k + 1}}</td>
      <td>{{ $v->kmatkul }}</td>
      <td>{{ $v->nmatkul }}</td>
      <td>{{ $v->sks }}</td>
      <td>{{ $v->n_kual }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
<script src="[js/jquery-3.5.0.min.js"></script>
<script src="[js/bootstrap/bootstrap.min.js"></script>
<script>
  var user_id = Android.User(4);  
  alert(nim);
</script>
</body>
</html>