<div class="add" ng-controller="LicenseCreateControler as license">
  <div class="al_form">
    <h2>Add a new license</h2>
    <h3>Custom License-Key</h3>
    <div class="input-group" style="width: 80%">
      <span class="input-group-addon">
        <input type="checkbox" ng-change="license.toggleDis('cKey')" ng-model="license.cKey" aria-label="...">
      </span>
      <input type="text" id="cKey" class="form-control" ng-model="license.userKey" aria-label="..." disabled>
    </div>
    <h4>Key: {{license.getKey()}}</h4>


    <h3>IP-Settings</h3>
    How many different IPs can access the key at the same time
    <div class="input-group" style="width: 80%">
      <span class="input-group-addon" >Number of different IPs</span>
      <input id="ips" type="number" value="1" class="form-control" aria-describedby="sizing-addon2">
    </div>


    <!--
    <h3>Pre-Defined IPs</h3>
    <p>
      If not entered, every IP will be able to use the Key [Recommended]
    </p>
    <div class="input-group" style="width: 80%">
      <span class="input-group-addon">
        <input type="checkbox" aria-label="...">
      </span>
      <input type="text" class="form-control" aria-label="...">
    </div>
    -->


    <h3>Expires in</h3>
    <div class="input-group" style="width: 80%" ng-init="dur = 'Never'">
      <input id="datePicker" type="text" ng-model="license.expireInt" class="form-control" disabled aria-label="...">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{license.expireDur}}<span class="caret"></span></button>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a ng-click="license.setDurType('Months')" >Months</a></li>
          <li><a ng-click="license.setDurType('Days')" >Days</a></li>
          <li><a ng-click="license.setDurType('Hours')" >Hours</a></li>
          <li role="separator" class="divider"></li>
          <li><a ng-click="license.setDurType('Never')" >Never</a></li>
        </ul>
      </div>
    </div>


    <h3>Additional information [Optional]</h3>


    <div class="input-group" style="width: 40%; float:left">
      <span class="input-group-addon" style="width: 120px;">Plugin-Name</span>
      <input id="plName" type="text" class="form-control" aria-describedby="sizing-addon2">
    </div>
    <div style="width: 40%; margin-left: 20px; margin-top:7px; float:left;">
      <input ng-model="license.plBound" type="checkbox"> License works only for the plugin with this name
    </div>

    <div class="input-group" style="width: 80%">
      <span class="input-group-addon" style="width: 120px;">Desc</span>
      <input id="plDesc" type="text" class="form-control" aria-describedby="sizing-addon2">
    </div>
    <div class="input-group" style="width: 80%">
      <span class="input-group-addon" style="width: 120px;">Client name</span>
      <input id="plClient" type="text" class="form-control" aria-describedby="sizing-addon2">
    </div>

    <div ng-click='license.submit()' class='al_btn al_submit'>
      <div class='anim_btn al_submit'>
         Create new
      </div>
       Create new
    </div>
  </div>
  <div class="al_fin" style="display: none;">
    <h1>{{license.finID}}</h1>
    <h3>Key >> {{license.getKey()}}</h3>
    <h3>Go to "Manage Licenses" to see/edit all license-data</h3>
  </div>
</div>
