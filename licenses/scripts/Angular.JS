(function() {
  var app = angular.module('License', []);

  app.controller("LicenseCreateControler",['$http', function($http){
    this.cKey = false;
    this.randKey = Math.random().toString(36).substr(2, 4).toUpperCase()+"-"+
                   Math.random().toString(36).substr(2, 4).toUpperCase()+"-"+
                   Math.random().toString(36).substr(2, 4).toUpperCase()+"-"+
                   Math.random().toString(36).substr(2, 4).toUpperCase();
    this.userKey = this.randKey;
    this.expireDur = "Never";
    this.expireInt = '';
    this.plBound = false;

    this.finID = 'None';

    var instance = this;

    this.getKey = function(){
      if(this.cKey) return this.userKey;
      else return this.randKey;
    };

    this.disableDur = function(){
      if(this.expireDur == 'Never') return 'disabled';
      return '';
    };

    this.setDurType = function(type){
      this.expireDur = type;

      if(type != 'Never') document.getElementById('datePicker').removeAttribute('disabled');
      else document.getElementById('datePicker').setAttribute('disabled', '');
    };

    this.toggleDis = function(id) {
      if(document.getElementById(id).hasAttribute('disabled')) document.getElementById(id).removeAttribute('disabled');
      else document.getElementById(id).setAttribute('disabled', '');
    };

    this.submit = function() {

      $(".newRating").slideToggle();

      console.log("Called SUBMIT!");
      var key = (this.cKey ? this.userKey : this.randKey);
      var ips = document.getElementById('ips').value;
        if(ips <= 0) ips = 1;
      var expDate = new Date();
        if(this.expireDur == 'Months') expDate.setMonth(    parseInt(expDate.getMonth())+parseInt(this.expireInt));
        else if(this.expireDur == 'Days') expDate.setDate(  parseInt(expDate.getDate()) +parseInt(this.expireInt));
        else if(this.expireDur == 'Hours') expDate.setHours(parseInt(expDate.getHours())+parseInt(this.expireInt));
        else expDate = null;
        if(expDate != null) expDate = expDate.getTime();
      var dataName   = document.getElementById('plName')  .value;
      var dataDesc   = document.getElementById('plDesc')  .value;
      var dataClient = document.getElementById('plClient').value;

      var nameBound  = this.plBound;
      $http.get("scripts/Action.php?action=create&key="+key+"&ips="+ips+"&expDate="+expDate+"&dName="+dataName+"&dDesc="+dataDesc+"&dClient="+dataClient+"&dBound="+nameBound).success(function(data){
        instance.finID = data;
      });
      $(".al_form").slideToggle();
      $(".al_fin").slideToggle();
    };

  }]);
})();
