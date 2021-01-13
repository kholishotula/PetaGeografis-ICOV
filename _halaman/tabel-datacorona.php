<?php
  $title="Tabel Data Penyebaran Virus Corona - ".date('d F Y');
  $judul=$title;
  $url='tabel-datacorona';
 ?>
<?=content_open($title)?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Provinsi</th>
        <th>Kasus Positif</th>
        <th>Kasus Sembuh</th>
        <th>Kasus Meninggal</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // dari https://bnpb-inacovid19.hub.arcgis.com/datasets/data-harian-kasus-per-provinsi-covid-19-indonesia/geoservice
        $url = "https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/COVID19_Indonesia_per_Provinsi/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json";
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);	
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type : application/json',
            'Access-Control-Allow-Origin : *'
          ]);
        $result= curl_exec($ch);
        curl_close($ch);
      
        $no=1;
        $getdata=json_decode($result, true);
        $coronaData=$getdata['features'];
        foreach ($coronaData as $row) {
          ?>
            <tr>
              <td><?=$no?></td>
              <td><?php echo $row['attributes']['Provinsi']?></td>
              <td><?php echo $row['attributes']['Kasus_Posi']?></td>
              <td><?php echo $row['attributes']['Kasus_Semb']?></td>
              <td><?php echo $row['attributes']['Kasus_Meni']?></td>
            </tr>
          <?php
          $no++;
        }

      ?>
    </tbody>
  </table>
<?=content_close()?>
