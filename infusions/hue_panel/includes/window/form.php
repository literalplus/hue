<html>
<head>
<script type="text/javascript" src="javascripts/prototype.js"></script> 
<script type="text/javascript" src="javascripts/effects.js"></script>
<script type="text/javascript" src="javascripts/window.js"></script>
<script type="text/javascript" src="javascripts/window_effects.js"></script>

<link href="themes/default.css" rel="stylesheet" type="text/css" ></link>
<link href="themes/spread.css" rel="stylesheet" type="text/css" ></link>
</head>
<body>

<div id="myloginform" style="display:none;overflow:clip;padding:10px;">
<form id="loginfrm">
<table>
<tr><td>Login</td><td><input type="text" name="name" /></td></tr>
<tr><td>Password</td><td><input type="password" name="password" /></td></tr>
</table>
</form>
<button onclick="login()">Login</button>
</div>

<a href="javascript:void showWindow();">Login</a>
<script>
var g_loginWindow = null;

function login()
{
  new Ajax.Request( 'login.php', {
    parameters: $('loginfrm').serialize(),
    method: 'post',
    onSuccess: function( transport ) {
       g_loginWindow.close();
    }
  } );
}

function showWindow()
{
  g_loginWindow = new Window( { className: 'spread', title: "Login",
    destroyOnClose: true,
    onClose:function() { $('myloginform').style.display = 'none'; } } ); 
  g_loginWindow.setContent( 'myloginform', true, true );
  g_loginWindow.showCenter();
}
</script>

</body>
</html>
