<script>
  function dateToString(milliseconds, def) {
    var d = new Date(milliseconds);
    console.log(milliseconds+" - "+d);
    if(d.getFullYear() == 1970) return def;
    return d.toLocaleDateString()+" - "+d.toLocaleTimeString();
  }
</script>

<?php


#if(!isset($_GET["refresh"])) header("Refresh:0; url=?page=manage&refresh=no");

function getLastRef($time="0") {
  foreach (explode("#", $time) as $str) {
    if($str > $time) $time = $str;
  }
  if(!$time) return "0";
  else return $time."000";
}
?>

<div class="manage">
  <script>
    function deleteLic(elementID, id) {
      console.log("Deleting license with id "+id+" -/- "+elementID);
      var xhttp = new XMLHttpRequest();
      xhttp.open("GET", "scripts/Action.php?action=delete&id="+id, true);
      xhttp.send();
      $(".header"+elementID).slideUp();
      $("#entry"+elementID).slideUp();
    }
  </script>
  <h1>Manage licenses</h1>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Key</th>
        <th>IPs</th>
        <th>Expiry date</th>
        <th>Last request</th>
        <th> </th>
      </tr>
    </thead>
    <tbody>
      <?php
        $sql= "SELECT * FROM `licenses`";
        $result=$link->query($sql);
        if($result->num_rows > 0) {
        	for($i=0; $i<$result->num_rows; $i++) {
            $cancelStlye='';
            if(mysqli_result($result,$i,'expiry') != -1 AND mysqli_result($result,$i,'expiry') < time()*1000) $cancelStlye = "class='expired'";

            echo "<tr style='cursor: pointer;' $cancelStlye onclick='$(\"#entry$i\").slideToggle();'>";
            echo "<td class='header$i' > ".mysqli_result($result,$i,'key')."</td>";
            echo "<td class='header$i' > ".mysqli_result($result,$i,'ips')."</td>";
            echo "<td class='header$i'  id='date$i'></td>";
            echo "<td class='header$i'  id='date$i-2'></td>";
            echo "<td class='header$i'> <i class='fa fa-mouse-pointer'></i></td>";
            echo "<script> document.getElementById('date$i').innerHTML = dateToString(".mysqli_result($result,$i,'expiry').", 'Never'); </script>";
            echo "<script> document.getElementById('date$i-2').innerHTML = dateToString(".getLastRef(mysqli_result($result,$i,'lastRef')).", 'None yet'); </script>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='5' id='entry$i' style='display: none;'>";
            ?>
            <div style="width: 40%; float: left;">
              <h5><strong>Plugin-Name » </strong><?php echo mysqli_result($result,$i,'plName'); ?></h5>
              <h5><strong>Description » </strong><?php echo mysqli_result($result,$i,'plDesc'); ?></h5>
              <h5><strong>Client-Info » </strong><?php echo mysqli_result($result,$i,'plClient'); ?></h5>
            </div>
            <div style="width: 20%; float: left;">
              <h5><strong>« Last IPs »</strong></h5>
              <?php
              $ips = explode('#', mysqli_result($result,$i,'currIPs'));

              if(mysqli_result($result,$i,'currIPs')) foreach ($ips as $value) { echo "<h5>- $value </h5>"; }
              else echo "No IP yet!";
              ?>
            </div>
            <div style="width: 40%; float: left;">
              <a href="#"><div onclick="deleteLic(<?php echo $i; ?>, <?php echo mysqli_result($result,$i,'id'); ?>)" class='al_btn al_delete' style="float: right;">
                <div class='anim_btn al_delete'>
                   Delete
                </div>
                 Delete
              </div> </a>
            </div>
            </div>
            <?php
            echo "</td>";
            echo "</tr>";
        	}
        } else { echo "<h3 style='color: #ff4a4a'>There are no licenses yet</h3>"; }
      ?>
    </tbody>
  </table>
</div>
