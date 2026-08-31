// ------------------------------------------------------
// REGISTER FORM VALIDATION
// register.php calls this function before form submission.
// ------------------------------------------------------
function validateRegisterForm() {
  var password = document.getElementById("password").value;
  var confirmPassword = document.getElementById("confirm_password").value;

  if (password.length < 8) {
    alert("Password must be at least 8 characters.");
    return false;
  }

  if (password != confirmPassword) {
    alert("Password and confirm password do not match.");
    return false;
  }

  return true;
}

// ------------------------------------------------------
// AJAX 1: CHECK EMAIL
// register.php calls this function when the email field loses focus.
// ------------------------------------------------------
function checkEmail() {
  var emailField = document.getElementById("email");
  var messageBox = document.getElementById("emailMessage");

  // This function is also loaded on pages that do not have an email field.
  // In that case there is nothing to do.
  if (emailField == null || messageBox == null) {
    return;
  }

  var email = emailField.value;

  if (email == "") {
    messageBox.innerHTML = "";
    return;
  }

  messageBox.innerHTML = "Checking email...";
  messageBox.className = "";

  var request = new XMLHttpRequest();

  // Date.now() is added only to stop the browser from showing an old cached result.
  var url = "ajax/check_email.php?email=" + encodeURIComponent(email);
  url = url + "&time=" + new Date().getTime();

  request.open("GET", url, true);

  request.onreadystatechange = function () {
    if (request.readyState == 4) {
      if (request.status == 200) {
        try {
          var data = JSON.parse(request.responseText);
          messageBox.innerHTML = data.message;

          if (data.available == true) {
            messageBox.className = "email-ok";
          } else {
            messageBox.className = "email-bad";
          }
        } catch (error) {
          messageBox.innerHTML = "Could not read server response.";
          messageBox.className = "email-bad";
        }
      } else {
        messageBox.innerHTML = "Could not check email.";
        messageBox.className = "email-bad";
      }
    }
  };

  request.onerror = function () {
    messageBox.innerHTML = "Could not connect to the server.";
    messageBox.className = "email-bad";
  };

  request.send();
}

// ------------------------------------------------------
// AJAX 2: REFRESH FEATURED PRODUCTS
// The Refresh button on the Home page directly calls this function.
// ------------------------------------------------------

async function loadFeaturedProducts() {
  const productList = document.getElementById("productList");
  const refreshButton = document.getElementById("refreshButton");
  const refreshMessage = document.getElementById("refreshMessage");

  // Guard clause: exit early if elements are missing
  if (!productList || !refreshButton || !refreshMessage) return;

  // Helper to update feedback text and styling
  const updateMessage = (text, type = "info") => {
    refreshMessage.textContent = text;
    refreshMessage.className = `refresh-message ${type}-text`.trim();
  };

  // Helper to toggle button loading state
  const setLoadingState = (isLoading) => {
    refreshButton.disabled = isLoading;
    refreshButton.textContent = isLoading ? "Refreshing..." : "Refresh";
  };

  try {
    setLoadingState(true);
    updateMessage("Loading latest products...", "info");

    // Cache-busting URL parameter
    const url = `ajax/featured_products.php?time=${Date.now()}`;
    const response = await fetch(url);

    if (!response.ok) {
      throw new Error(`Server error: ${response.status}`);
    }

    const data = await response.json();

    if (data.success) {
      productList.innerHTML = data.html;
      updateMessage(`${data.message} Time: ${data.refreshed_at}`, "success");
    } else {
      updateMessage(data.message || "Failed to load products.", "error");
    }
  } catch (error) {
    const errorMsg = error instanceof SyntaxError 
      ? "Server response is not valid JSON." 
      : `Refresh failed. ${error.message}`;

    updateMessage(errorMsg, "error");
  } finally {
    setLoadingState(false);
  }
}
