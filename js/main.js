//function which runs ajax call which do login stuff on server
//for more info look at php/login.php
function login(username, password, f, newpassword, usermail, callback){
  $.ajax({
      type: 'POST',
      url: 'php/login.php',
      data: {username: username, password: password, f: f, newpassword: newpassword, usermail: usermail},
      cache: false,
      success: function(status) {
        console.log("login status: "+status);
        callback(status);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError + xhr);
      }
  });
}

//this function handels the buttons which are used for login stuff
//run login function with right parameters
//change designe and info on the site
function loginfunction(){
  $('#submit_logout').click(function(){
    loginstatus = 0;
    login(0,0,'logout', 0, 0, function callback(loginstatus){
      $('#login_div_layer_1').css("display", "block");
      $('#login_div_layer_2').css("display", "none");
    });
  });

  $('.close_button').click(function(){
    $('#login_div').css("display", "none");
    open_login_status = 0;
  });

  var open_login_status = 0;
  $('#open_login').click(function(){
    if (open_login_status == 0) {
      $('#login_div').css("display", "block");
      login(0,0,'checklogin', 0, 0, function callback(loginstatus){
        if (loginstatus == 1) {
          $('#login_div_layer_1').css("display", "none");
          $('#login_div_layer_2').css("display", "block");
          var username = sessionStorage.getItem('username');
          $('#login_div_layer_2 h3').text('Hello '+username);
        }
        else {
          $('#login_div_layer_1').css("display", "block");
          $('#login_div_layer_2').css("display", "none");
          $('#submit_login').click(function(){
            var username = $('#login_username').val();
            var password = $('#login_password').val();
            login(username, password, 'login', 0, 0, function callback(loginstatus){
              if (loginstatus == 1) {
                login(0, 0, 'getuserdata', 0, 0, function callback(data){
                  var userdata = JSON.parse(data);
                  sessionStorage.setItem('username', username);
                  sessionStorage.setItem('usermail', userdata[0]);
                  $('#login_div_layer_1').css("display", "none");
                  $('#login_div_layer_2').css("display", "block");
                  $('#login_div_layer_2 h3').text('Hello '+username);
                });
              }
              else if (loginstatus == 2) {
                alert('user dont exist');
              }
              else if (loginstatus == 3) {
                alert('password incorect');
              }
              else if (loginstatus == 4) {
                alert('general error');
              }
            });
          });
        }
      });
      open_login_status = 1;
    }
    else if(open_login_status == 1) {
      $('#login_div').css("display", "none");
      open_login_status = 0;
    }
  });
}

function get_settings(object_name, callback){
  //start ajax request
    $.ajax({
        url: "./settings.json",
        //force to handle it as text
        dataType: "text",
        success: function(data) {

            //data downloaded so we call parseJSON function
            //and pass downloaded data
            var json = $.parseJSON(data);
            callback(json[object_name]);
        }
    });
}

//load nav with array info
function loadnav(){
  //get nav points from settings file
  get_settings("navigation", function(navdata){
    //get div for nav and show nav data in it
    var navul = document.getElementById("navul_1");
    for (var i = 0; i < navdata.length; i++) {
      var url = navdata[i][0];
      var name = navdata[i][1];
      var id = navdata [i][2];
      var a = document.createElement("a");
      if (url != 0) {
        a.setAttribute('href', url);
      }
      var li = document.createElement("li");
      li.setAttribute('class', 'navpos');
      li.setAttribute('id', id);
      var litxt = document.createTextNode(name);
      li.appendChild(litxt);
      a.appendChild(li)
      navul.appendChild(a);
      loginfunction();
    }

    //used on mobile to open/close nav
    $('#nav_open').click(function(){
      $('#navinfo_2').animate({
          top: '0vh'
      }, 200);
    });

    $('#nav_close').click(function(){
      $('#navinfo_2').animate({
          top: '-100vh'
      }, 200);
    });
['index.html?p=trackupload.html', 'UPLOAD', 'navpos2'],
    //open login window
    $('#open_login').click(function(){
      $('#navinfo_2').animate({
          top: '-100vh'
      }, 200);
    });
  });
}

//get value from url (GET) requires variable name
function get(name){
   if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
      return decodeURIComponent(name[1]);
}

//this function runs on siteload
$(document).ready(function(){
  loadnav();

  //p = page variable = which page shoud be loaded
  var site = get('p');
  //if empty load index.html
  if (site == null) {
    site = 'index.html';
  }
  $('#MAIN').load("include/"+site, function(){
    if (site == 'index.html') {

    }
  });
});
