function toggleOptions(parentOption, childOptions) {
	var display = '';

	if (document.getElementById(parentOption).checked == false) {
		display = 'none';
	}
	for (var i = 0; i < childOptions.length; i++) {
		console.log(childOptions[i]);
		console.log(document.getElementById(childOptions[i]));
		document.getElementById(childOptions[i]).style.display = display;
	}
}

function authFacebook() {
	FB.login(function(response) {
		if (response.authResponse) {
			redirectWithParam('fb_extended_token', 1);
		} else {
			console.log('User cancelled login or did not fully authorize.');
		}
	}, {scope: 'manage_pages, publish_actions, publish_stream'});
}


function redirectWithParam(key, value) {
	key = escape(key); value = escape(value);

	var kvp = document.location.search.substr(1).split('&');
	if (kvp == '') {
		document.location.search = '?' + key + '=' + value;
	}
	else {

		var i = kvp.length; var x; while (i--) {
			x = kvp[i].split('=');

			if (x[0] == key) {
				x[1] = value;
				kvp[i] = x.join('=');
				break;
			}
		}

		if (i < 0) { kvp[kvp.length] = [key, value].join('='); }
    
		document.location.search = kvp.join('&');
	}
}

jQuery(function() {
	jQuery("#suggest-friends").tokenInput("?fb-friends=1", {
		theme: "facebook",
		preventDuplicates: true,
		hintText: "Type to find a friend."
	});
});;

function fbShowDebugInfo() {
  $('#debug-output').show();
  
  return false;
}